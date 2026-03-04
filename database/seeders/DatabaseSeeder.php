<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Chairman
        if (!User::where('email', 'chairman@subeb.test')->exists()) {
            User::create([
                'name' => 'Chairman',
                'email' => 'chairman@subeb.test',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CHAIRMAN,
                'is_active' => true,
            ]);
        }

        // Seed Director
        if (!User::where('email', 'director@subeb.test')->exists()) {
            User::create([
                'name' => 'Director',
                'email' => 'director@subeb.test',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DIRECTOR,
                'is_active' => true,
            ]);
        }
    }
}
