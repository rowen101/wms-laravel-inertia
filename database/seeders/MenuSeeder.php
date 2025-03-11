<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
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
