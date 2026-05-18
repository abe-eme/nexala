<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MASTER ADMIN ACCOUNT
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'status' => 'approved',
            'ip_address' => '127.0.0.1',
            'password' => Hash::make('password123'), // Change this in production!
        ]);

        // 2. DEVELOPER / TEACHER BYPASS ACCOUNT (For testing)
        User::create([
            'name' => 'Developer Account',
            'email' => 'developer@gmail.com',
            'role' => 'teacher',
            'status' => 'approved',
            'ip_address' => '127.0.0.1',
            'password' => Hash::make('password123'),
        ]);
        
        // 3. EVALUATOR BYPASS ACCOUNT (For your grader)
        User::create([
            'name' => 'Project Evaluator',
            'email' => 'evaluator@gmail.com',
            'role' => 'teacher',
            'status' => 'approved',
            'ip_address' => '127.0.0.1',
            'password' => Hash::make('password123'),
        ]);
    }
}