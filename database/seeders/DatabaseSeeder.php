<?php

namespace Database\Seeders;

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

        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@kjpp.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        \App\Models\User::create([
            'name' => 'Debitur',
            'email' => 'debitur@kjpp.com',
            'role' => 'debitur',
            'password' => Hash::make('password')
        ]);

        \App\Models\User::create([
            'name' => 'Surveyor',
            'email' => 'surveyor@kjpp.com',
            'role' => 'surveyor',
            'password' => Hash::make('password')
        ]);
    }
}
