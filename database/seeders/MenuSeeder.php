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
    Menu::create(['MenuText' => 'Dashboard', 'FormName' => 'dashboard', 'ParentMenuID' => null]);
    Menu::create(['MenuText' => 'Users', 'FormName' => 'users.index', 'ParentMenuID' => null]);
    }
}
