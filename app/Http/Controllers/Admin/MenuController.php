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
    public function index()
    {
        // Example: Fetch all menus for admin management
        $menus = Menu::all();

        return Inertia::render('Admin/Menus/Index', [
            'menus' => $menus,
        ]);
    }
}