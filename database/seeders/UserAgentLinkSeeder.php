<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;

class UserAgentLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNotIn('email', ['admin@rahlety.com', 'superadmin@rahlety.com'])->get();
        $agents = Agent::all();
        
        if ($users->isEmpty() || $agents->isEmpty()) {
            $this->command->error('Users or agents not found. Run UserSeeder and AgentSeeder first.');
            return;
        }

        // Link approximately 30% of users to agents (referral relationships)
        $usersToLink = $users->random(min($users->count(), (int)($users->count() * 0.3)));
        
        foreach ($usersToLink as $user) {
            $agent = $agents->random();
            $user->update(['agent_id' => $agent->id]);
        }
        
        $linkedCount = $usersToLink->count();
        $this->command->info("Linked {$linkedCount} users to agents for referral tracking");
        
        // Display agent referral statistics
        foreach ($agents as $agent) {
            $referredCount = $agent->referredUsers()->count();
            if ($referredCount > 0) {
                $this->command->info("- {$agent->name}: {$referredCount} referred users");
            }
        }
    }
}