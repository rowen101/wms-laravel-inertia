<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\Menu;

class MenuController extends Controller
{
    public function getUserMenu(Request $request)
    {
        // Get the logged-in user
        $user = $request->user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the user's role ID
        $userRole = $user->user_role;

        // Find the role in the roles table
        $role = Role::find($userRole);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Get the menu IDs associated with the role from the role_menu table
        $menuIds = RoleMenu::where('role_id', $role->id)->pluck('menu_id');

        if ($menuIds->isEmpty()) {
            return response()->json(['error' => 'No menus found for this role'], 404);
        }

        // Fetch top-level menus (where parent_id is 0)
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
                    ];
                });
        });

        // Format the menus for the frontend
        $formattedMenus = $menus->map(function ($menu) {
            return [
                'title' => $menu->MenuText,
                'icon' => $menu->ImageName, // Assuming ImageName stores the icon
                'items' => $menu->items,
            ];
        });

        // Return the menus to the frontend using Inertia
        return Inertia::render('Dashboard', [
            'menus' => $formattedMenus,
        ]);
    }
}
