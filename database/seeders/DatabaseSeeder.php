<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
             'EmpID' => '001',
             'name' =>'rowen',
             'last_name' => 'Gonzales',
             'email'=>'rgrowengonzales66@gmail.com',
             'password'=>'admin123',
             'user_role'=>1
            ]);
    }
}
