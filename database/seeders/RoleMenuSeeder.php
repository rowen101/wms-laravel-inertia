<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;
class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        $dashboard = Menu::where('name', 'Dashboard')->first();
        $users = Menu::where('name', 'Users')->first();

        $adminRole->menus()->attach([$dashboard->id, $users->id]);
        $userRole->menus()->attach([$dashboard->id]);
    }
}
