<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Role;
use Inertia\Middleware;
use App\Models\RoleMenu;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Initialize menus as an empty array
        $menus = [];

        // Get the logged-in user
        $user = $request->user();

        // If the user is authenticated, fetch their menus
        if ($user) {
            // Get the user's role ID
            $userRole = $user->user_role;

            // Find the role in the roles table
            $role = Role::find($userRole);

            if ($role) {
                // Get the menu IDs associated with the role from the role_menu table
                $menuIds = RoleMenu::where('role_id', $role->id)->pluck('menu_id');

                if ($menuIds->isNotEmpty()) {
                    // Fetch top-level menus (where ParentMenuID is 0)
                    $menus = Menu::whereIn('MenuID', $menuIds)
                        ->where('ParentMenuID', 0)
                        ->orderBy('MenuOrder', 'ASC')
                        ->get();

                    // Fetch submenus for each top-level menu
                    $menus->each(function ($menu) use ($menuIds) {
                        $menu->items = Menu::whereIn('MenuID', $menuIds)
                            ->where('ParentMenuID', $menu->MenuID)
                            ->orderBy('MenuOrder', 'ASC')
                            ->get()
                            ->map(function ($submenu) {
                                return [
                                    'title' => $submenu->MenuText,
                                    'route' => $submenu->route, // Assuming route is stored in the Menu model
                                ];
                            });
                    });

                    // Format the menus for the frontend
                    $menus = $menus->map(function ($menu) {
                        return [
                            'title' => $menu->MenuText,
                            'icon' => $menu->ImageName, // Assuming ImageName stores the icon
                            'items' => $menu->items->isEmpty() ? null : $menu->items, // Only include items if they exist
                        ];
                    })->toArray();
                }
            }
        }

        // Merge the existing shared props with the new menus
        return [
            ...parent::share($request), // Keep existing shared props
            'auth' => [
                'user' => $request->user(), // Keep the auth user
            ],
            'menus' => $menus, // Add the menus
         
          
        ];
    }
}
