<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "TestSeeder is running!\n";
        
        // Create a simple user to test
        \App\Models\User::create([
            'name' => 'Test User from Seeder',
            'email' => 'seeder@test.com',
            'password' => bcrypt('password'),
            'phone' => '+1234567890',
            'language' => 'en',
            'country' => 'Test Country'
        ]);
        
        echo "TestSeeder created a user successfully!\n";
    }
}