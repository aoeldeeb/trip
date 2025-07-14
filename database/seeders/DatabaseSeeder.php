<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌊 Starting Rahlety Database Seeding...');
        $this->command->newLine();
        
        // Step 1: Create users (including admins)
        $this->command->info('👥 Creating users...');
        $this->call(UserSeeder::class);
        $this->command->newLine();
        
        // Step 2: Create agents
        $this->command->info('🤝 Creating agents...');
        $this->call(AgentSeeder::class);
        $this->command->newLine();
        
        // Step 2.5: Link users to agents for referral system
        $this->command->info('🔗 Linking users to agents...');
        $this->call(UserAgentLinkSeeder::class);
        $this->command->newLine();
        
        // Step 3: Create companies (some approved, some pending)
        $this->command->info('🏢 Creating companies...');
        $this->call(CompanySeeder::class);
        $this->command->newLine();
        
        // Step 4: Create trips (requires approved companies)
        $this->command->info('🗺️ Creating exciting trips...');
        $this->call(TripSeeder::class);
        $this->command->newLine();
        
        // Step 5: Create bookings (requires users, trips, and agents)
        $this->command->info('📅 Creating bookings...');
        $this->call(BookingSeeder::class);
        $this->command->newLine();
        
        // Step 6: Create reviews (requires completed bookings)
        $this->command->info('⭐ Creating reviews...');
        $this->call(ReviewSeeder::class);
        $this->command->newLine();
        
        // Step 7: Create commissions (requires bookings with agents)
        $this->command->info('💰 Creating commission records...');
        $this->call(CommissionSeeder::class);
        $this->command->newLine();
        
        // Final summary
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->newLine();
        
        $this->displaySummary();
    }
    
    private function displaySummary()
    {
        $this->command->info('📊 Database Summary:');
        $this->command->info('==================');
        
        $users = \App\Models\User::count();
        $admins = \App\Models\User::whereIn('email', ['admin@rahlety.com', 'superadmin@rahlety.com'])->count();
        $agents = \App\Models\Agent::count();
        $companies = \App\Models\Company::count();
        $approvedCompanies = \App\Models\Company::where('is_approved', true)->count();
        $trips = \App\Models\Trip::count();
        $bookings = \App\Models\Booking::count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $reviews = \App\Models\Review::count();
        $commissions = \App\Models\Commission::count();
        $totalRevenue = \App\Models\Booking::where('status', 'confirmed')->sum('total_price');
        $averageRating = round(\App\Models\Review::avg('rating'), 2);
        
        $this->command->info("👥 Users: {$users} (including {$admins} admins)");
        $this->command->info("🤝 Agents: {$agents}");
        $this->command->info("🏢 Companies: {$companies} ({$approvedCompanies} approved)");
        $this->command->info("🗺️ Trips: {$trips}");
        $this->command->info("📅 Bookings: {$bookings} ({$confirmedBookings} confirmed)");
        $this->command->info("⭐ Reviews: {$reviews} (avg: {$averageRating}/5)");
        $this->command->info("💰 Commissions: {$commissions}");
        $this->command->info("💵 Total Revenue: $" . number_format($totalRevenue, 2));
        
        $this->command->newLine();
        $this->command->info('🚀 Ready for testing!');
        $this->command->info('🎛️ Access admin dashboard: http://localhost:8000/admin/login');
        $this->command->info('🔑 Use any email/password to login as admin');
        $this->command->newLine();
        
        $this->command->info('📝 Sample Login Credentials:');
        $this->command->info('---------------------------');
        $this->command->info('Admin: admin@rahlety.com / admin123');
        $this->command->info('Agent: mohamed.elsayed@rahlety-agent.com / agent123');
        $this->command->info('Company: info@redseaadventures.com / company123');
        $this->command->info('User: ahmed.hassan@email.com / password123');
    }
}