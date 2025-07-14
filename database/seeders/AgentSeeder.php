<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            [
                'name' => 'Mohamed Elsayed',
                'email' => 'mohamed.elsayed@rahlety-agent.com',
                'phone' => '+201012345678',
                'bio' => 'Expert travel agent specializing in Red Sea destinations with 8 years of experience.',
                'created_at' => now()->subDays(25),
            ],
            [
                'name' => 'Nadia Ibrahim',
                'email' => 'nadia.ibrahim@rahlety-agent.com',
                'phone' => '+201087654321',
                'bio' => 'Luxury travel specialist focusing on premium Sinai Peninsula experiences.',
                'created_at' => now()->subDays(22),
            ],
            [
                'name' => 'Omar Farouk',
                'email' => 'omar.farouk@rahlety-agent.com',
                'phone' => '+201156789012',
                'bio' => 'Adventure travel expert with deep knowledge of South Sinai diving spots.',
                'created_at' => now()->subDays(20),
            ],
            [
                'name' => 'Layla Mansour',
                'email' => 'layla.mansour@rahlety-agent.com',
                'phone' => '+201234567890',
                'bio' => 'Family travel coordinator specializing in all-inclusive Red Sea packages.',
                'created_at' => now()->subDays(18),
            ],
            [
                'name' => 'Karim Habib',
                'email' => 'karim.habib@rahlety-agent.com',
                'phone' => '+201098765432',
                'bio' => 'Budget travel specialist helping young travelers explore Egypt on a budget.',
                'created_at' => now()->subDays(15),
            ],
            [
                'name' => 'Yasmin Mostafa',
                'email' => 'yasmin.mostafa@rahlety-agent.com',
                'phone' => '+201123456789',
                'bio' => 'Cultural tourism expert offering authentic Egyptian experiences.',
                'created_at' => now()->subDays(12),
            ],
            [
                'name' => 'Hassan Ali',
                'email' => 'hassan.ali@rahlety-agent.com',
                'phone' => '+201987654321',
                'bio' => 'Water sports enthusiast specializing in diving and snorkeling tours.',
                'created_at' => now()->subDays(10),
            ],
            [
                'name' => 'Mona Sayed',
                'email' => 'mona.sayed@rahlety-agent.com',
                'phone' => '+201345678901',
                'bio' => 'Wellness travel expert focusing on relaxation and spa retreats.',
                'created_at' => now()->subDays(8),
            ],
        ];

        foreach ($agents as $agentData) {
            // Generate unique referral code
            do {
                $referralCode = 'RHL' . strtoupper(Str::random(5));
            } while (Agent::where('referral_code', $referralCode)->exists());

            Agent::create([
                'name' => $agentData['name'],
                'email' => $agentData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('agent123'),
                'phone' => $agentData['phone'],
                'bio' => $agentData['bio'],
                'referral_code' => $referralCode,
                'commission_rate' => 5.00, // 5% commission rate
                'total_earned' => 0,
                'created_at' => $agentData['created_at'],
                'updated_at' => $agentData['created_at'],
            ]);
        }

        $this->command->info('Created ' . count($agents) . ' agents with unique referral codes');
    }
}