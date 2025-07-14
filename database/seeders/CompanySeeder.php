<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Red Sea Adventures',
                'email' => 'info@redseaadventures.com',
                'phone' => '+20693601234',
                'description' => 'Premier diving and snorkeling company based in Sharm El Sheikh. We offer world-class underwater experiences in the Red Sea with certified instructors and modern equipment.',
                'address' => 'Naama Bay, Sharm El Sheikh, South Sinai, Egypt',
                'license_number' => 'TSL-2019-001',
                'is_approved' => true,
                'created_at' => now()->subDays(60),
            ],
            [
                'name' => 'Sinai Explorer Tours',
                'email' => 'contact@sinaiexplorer.com',
                'phone' => '+20693602345',
                'description' => 'Specialized in desert safaris, mountain hiking, and cultural tours across the Sinai Peninsula. Experience authentic Bedouin culture and stunning landscapes.',
                'address' => 'Old Market, Sharm El Sheikh, South Sinai, Egypt',
                'license_number' => 'TSL-2019-002',
                'is_approved' => true,
                'created_at' => now()->subDays(55),
            ],
            [
                'name' => 'Dahab Marine Center',
                'email' => 'info@dahabmarine.com',
                'phone' => '+20693501234',
                'description' => 'Leading water sports center in Dahab offering kitesurfing, windsurfing, diving, and snorkeling. Perfect location in the windy bay of Dahab.',
                'address' => 'Lighthouse, Dahab, South Sinai, Egypt',
                'license_number' => 'TSL-2020-003',
                'is_approved' => true,
                'created_at' => now()->subDays(50),
            ],
            [
                'name' => 'Nuweiba Eco Tours',
                'email' => 'info@nuweiba-eco.com',
                'phone' => '+20693401234',
                'description' => 'Eco-friendly tourism company focusing on sustainable travel experiences in Nuweiba and surrounding areas. Coral reef conservation and marine life protection.',
                'address' => 'Nuweiba Port, South Sinai, Egypt',
                'license_number' => 'TSL-2020-004',
                'is_approved' => true,
                'created_at' => now()->subDays(45),
            ],
            [
                'name' => 'Desert Rose Expeditions',
                'email' => 'bookings@desertroseexp.com',
                'phone' => '+20693603456',
                'description' => 'Luxury desert camping and safari experiences. We provide high-end desert adventures with comfortable accommodations and gourmet meals.',
                'address' => 'Sharm El Sheikh, South Sinai, Egypt',
                'license_number' => 'TSL-2021-005',
                'is_approved' => true,
                'created_at' => now()->subDays(40),
            ],
            [
                'name' => 'Blue Hole Divers',
                'email' => 'dive@bluehole-dahab.com',
                'phone' => '+20693502345',
                'description' => 'Specialized diving center at the famous Blue Hole in Dahab. Offering technical diving courses and guided dives for experienced divers.',
                'address' => 'Blue Hole, Dahab, South Sinai, Egypt',
                'license_number' => 'TSL-2021-006',
                'is_approved' => true,
                'created_at' => now()->subDays(35),
            ],
            [
                'name' => 'Coral Garden Tours',
                'email' => 'info@coralgardentours.com',
                'phone' => '+20693604567',
                'description' => 'Family-friendly snorkeling and glass boat tours to the most beautiful coral reefs in the Red Sea. Perfect for all ages and swimming abilities.',
                'address' => 'Sharks Bay, Sharm El Sheikh, South Sinai, Egypt',
                'license_number' => 'TSL-2021-007',
                'is_approved' => true,
                'created_at' => now()->subDays(30),
            ],
            [
                'name' => 'Bedouin Heritage Tours',
                'email' => 'experience@bedouinheritage.com',
                'phone' => '+20693503456',
                'description' => 'Authentic Bedouin cultural experiences including camel trekking, traditional meals, and overnight camping in the desert with local Bedouin families.',
                'address' => 'Central Dahab, South Sinai, Egypt',
                'license_number' => 'TSL-2022-008',
                'is_approved' => true,
                'created_at' => now()->subDays(25),
            ],
            // Pending approval companies
            [
                'name' => 'New Horizon Adventures',
                'email' => 'info@newhorizonadv.com',
                'phone' => '+20693605678',
                'description' => 'New tour company specializing in adventure sports and extreme activities in the Red Sea region.',
                'address' => 'Naama Bay, Sharm El Sheikh, South Sinai, Egypt',
                'license_number' => 'TSL-2024-009',
                'is_approved' => false,
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Crystal Waters Diving',
                'email' => 'info@crystalwatersdiving.com',
                'phone' => '+20693504567',
                'description' => 'Modern diving center with latest equipment and experienced multilingual instructors.',
                'address' => 'Assalah, Dahab, South Sinai, Egypt',
                'license_number' => 'TSL-2024-010',
                'is_approved' => false,
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Nomad Desert Journeys',
                'email' => 'travel@nomaddesert.com',
                'phone' => '+20693402345',
                'description' => 'Boutique travel company offering personalized desert experiences and cultural immersion programs.',
                'address' => 'Nuweiba, South Sinai, Egypt',
                'license_number' => 'TSL-2024-011',
                'is_approved' => false,
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($companies as $companyData) {
            Company::create([
                'name' => $companyData['name'],
                'email' => $companyData['email'],
                'email_verified_at' => $companyData['is_approved'] ? now() : null,
                'password' => Hash::make('company123'),
                'phone' => $companyData['phone'],
                'description' => $companyData['description'],
                'address' => $companyData['address'],
                'license_number' => $companyData['license_number'],
                'is_approved' => $companyData['is_approved'],
                'created_at' => $companyData['created_at'],
                'updated_at' => $companyData['created_at'],
            ]);
        }

        $approvedCount = collect($companies)->where('is_approved', true)->count();
        $pendingCount = collect($companies)->where('is_approved', false)->count();
        
        $this->command->info('Created ' . count($companies) . ' companies (' . $approvedCount . ' approved, ' . $pendingCount . ' pending)');
    }
}