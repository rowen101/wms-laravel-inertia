<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create(['name' => 'Dashboard', 'route' => 'dashboard', 'parent_menu' => null]);
    Menu::create(['name' => 'Users', 'route' => 'users.index', 'parent_menu' => null]);
    }
}
