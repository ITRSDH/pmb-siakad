<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users (use delete to avoid resetting auto-increment in some DBs)
        \App\Models\User::query()->delete();

        // Admin user
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Teacher user
        \App\Models\User::create([
            'name' => 'Teacher One',
            'email' => 'teacher@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('teacher123'),
            'email_verified_at' => now(),
        ]);

        // Student user
        \App\Models\User::create([
            'name' => 'Student One',
            'email' => 'student@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('student123'),
            'email_verified_at' => now(),
        ]);
    }
}
