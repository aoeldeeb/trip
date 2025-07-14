<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "UserSeeder: Starting user creation...\n";
        
        // Create admin users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rahlety.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'phone' => '+201234567890',
            'language' => 'en',
            'country' => 'Egypt',
            'created_at' => now()->subDays(30),
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@rahlety.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin123'),
            'phone' => '+201987654321',
            'language' => 'en',
            'country' => 'Egypt',
            'created_at' => now()->subDays(25),
        ]);

        // Regular users from various countries
        $users = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@email.com',
                'phone' => '+201123456789',
                'language' => 'ar',
                'country' => 'Egypt',
                'created_at' => now()->subDays(20),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '+447700900123',
                'language' => 'en',
                'country' => 'United Kingdom',
                'created_at' => now()->subDays(18),
            ],
            [
                'name' => 'Marco Rossi',
                'email' => 'marco.rossi@email.com',
                'phone' => '+393471234567',
                'language' => 'en',
                'country' => 'Italy',
                'created_at' => now()->subDays(15),
            ],
            [
                'name' => 'Hans Mueller',
                'email' => 'hans.mueller@email.com',
                'phone' => '+491701234567',
                'language' => 'en',
                'country' => 'Germany',
                'created_at' => now()->subDays(12),
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@email.com',
                'phone' => '+61412345678',
                'language' => 'en',
                'country' => 'Australia',
                'created_at' => now()->subDays(10),
            ],
            [
                'name' => 'Pierre Dupont',
                'email' => 'pierre.dupont@email.com',
                'phone' => '+33612345678',
                'language' => 'en',
                'country' => 'France',
                'created_at' => now()->subDays(8),
            ],
            [
                'name' => 'Fatima Al-Zahra',
                'email' => 'fatima.alzahra@email.com',
                'phone' => '+201098765432',
                'language' => 'ar',
                'country' => 'Egypt',
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+12125551234',
                'language' => 'en',
                'country' => 'United States',
                'created_at' => now()->subDays(6),
            ],
            [
                'name' => 'Yuki Tanaka',
                'email' => 'yuki.tanaka@email.com',
                'phone' => '+819012345678',
                'language' => 'en',
                'country' => 'Japan',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Mikhail Petrov',
                'email' => 'mikhail.petrov@email.com',
                'phone' => '+79261234567',
                'language' => 'en',
                'country' => 'Russia',
                'created_at' => now()->subDays(4),
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@email.com',
                'phone' => '+919876543210',
                'language' => 'en',
                'country' => 'India',
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Carlos Rodriguez',
                'email' => 'carlos.rodriguez@email.com',
                'phone' => '+34612345678',
                'language' => 'en',
                'country' => 'Spain',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Amina Kone',
                'email' => 'amina.kone@email.com',
                'phone' => '+201345678901',
                'language' => 'ar',
                'country' => 'Egypt',
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@email.com',
                'phone' => '+16471234567',
                'language' => 'en',
                'country' => 'Canada',
                'created_at' => now(),
            ],
        ];

        foreach ($users as $index => $userData) {
            // Assign some users to agents (30% chance)
            $agentId = null;
            if (rand(1, 100) <= 30) {
                // We'll update this after agents are created
                $agentId = 'PLACEHOLDER_' . ($index % 3 + 1); // Will be replaced later
            }

            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => $userData['phone'],
                'language' => $userData['language'],
                'country' => $userData['country'],
                'agent_id' => null, // Will be set later after agents exist
                'created_at' => $userData['created_at'],
                'updated_at' => $userData['created_at'],
            ]);
        }

        // Store user indices for later agent assignment
        $this->command->info('Users will be linked to agents after AgentSeeder runs');

        $this->command->info('Created ' . (count($users) + 2) . ' users (including 2 admins)');
    }
}