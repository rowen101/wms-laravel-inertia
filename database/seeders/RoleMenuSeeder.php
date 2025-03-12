<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $adminRole = Role::where('name', 'Admin')->first();
    $userRole = Role::where('name', 'User')->first();

    $dashboard = Menu::where('MenuText', 'Dashboard')->first();
    $users = Menu::where('MenuText', 'Users')->first();

    $adminRole->menus()->attach([$dashboard->MenuId, $users->id]);
    $userRole->menus()->attach([$dashboard->id]);
    }
}
