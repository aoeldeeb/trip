<?php

// Manual Database Seeding Script for Rahlety
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "🌊 Starting Manual Rahlety Database Seeding...\n\n";

try {
    // Create Admin Users
    echo "👥 Creating admin users...\n";
    
    $admin1 = App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@rahlety.com',
        'email_verified_at' => now(),
        'password' => bcrypt('admin123'),
        'phone' => '+201234567890',
        'language' => 'en',
        'country' => 'Egypt',
        'created_at' => now()->subDays(30),
    ]);
    
    $admin2 = App\Models\User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@rahlety.com',
        'email_verified_at' => now(),
        'password' => bcrypt('superadmin123'),
        'phone' => '+201987654321',
        'language' => 'en',
        'country' => 'Egypt',
        'created_at' => now()->subDays(25),
    ]);
    
    echo "✅ Created 2 admin users\n\n";
    
    // Create Regular Users
    echo "👤 Creating regular users...\n";
    
    $users = [
        ['name' => 'Ahmed Hassan', 'email' => 'ahmed.hassan@email.com', 'phone' => '+201123456789', 'language' => 'ar', 'country' => 'Egypt'],
        ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@email.com', 'phone' => '+447700900123', 'language' => 'en', 'country' => 'United Kingdom'],
        ['name' => 'Marco Rossi', 'email' => 'marco.rossi@email.com', 'phone' => '+393471234567', 'language' => 'en', 'country' => 'Italy'],
        ['name' => 'Emma Wilson', 'email' => 'emma.wilson@email.com', 'phone' => '+61412345678', 'language' => 'en', 'country' => 'Australia'],
        ['name' => 'John Smith', 'email' => 'john.smith@email.com', 'phone' => '+12125551234', 'language' => 'en', 'country' => 'United States'],
    ];
    
    foreach ($users as $userData) {
        App\Models\User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'phone' => $userData['phone'],
            'language' => $userData['language'],
            'country' => $userData['country'],
            'created_at' => now()->subDays(rand(1, 20)),
        ]);
    }
    
    echo "✅ Created " . count($users) . " regular users\n\n";
    
    // Create Agents
    echo "🤝 Creating agents...\n";
    
    $agent1 = App\Models\Agent::create([
        'name' => 'Mohamed Elsayed',
        'email' => 'mohamed.elsayed@rahlety-agent.com',
        'email_verified_at' => now(),
        'password' => bcrypt('agent123'),
        'phone' => '+201012345678',
        'bio' => 'Expert travel agent specializing in Red Sea destinations with 8 years of experience.',
        'referral_code' => 'RHLA001',
        'commission_rate' => 5.00,
        'total_earned' => 0,
        'created_at' => now()->subDays(25),
    ]);
    
    $agent2 = App\Models\Agent::create([
        'name' => 'Nadia Ibrahim',
        'email' => 'nadia.ibrahim@rahlety-agent.com',
        'email_verified_at' => now(),
        'password' => bcrypt('agent123'),
        'phone' => '+201087654321',
        'bio' => 'Luxury travel specialist focusing on premium Sinai Peninsula experiences.',
        'referral_code' => 'RHLA002',
        'commission_rate' => 5.00,
        'total_earned' => 0,
        'created_at' => now()->subDays(22),
    ]);
    
    echo "✅ Created 2 agents\n\n";
    
    // Create Companies
    echo "🏢 Creating companies...\n";
    
    $company1 = App\Models\Company::create([
        'name' => 'Red Sea Adventures',
        'email' => 'info@redseaadventures.com',
        'email_verified_at' => now(),
        'password' => bcrypt('company123'),
        'phone' => '+20693601234',
        'description' => 'Premier diving and snorkeling company based in Sharm El Sheikh. We offer world-class underwater experiences in the Red Sea with certified instructors and modern equipment.',
        'address' => 'Naama Bay, Sharm El Sheikh, South Sinai, Egypt',
        'license_number' => 'TSL-2019-001',
        'is_approved' => true,
        'created_at' => now()->subDays(60),
    ]);
    
    $company2 = App\Models\Company::create([
        'name' => 'Dahab Marine Center',
        'email' => 'info@dahabmarine.com',
        'email_verified_at' => now(),
        'password' => bcrypt('company123'),
        'phone' => '+20693501234',
        'description' => 'Leading water sports center in Dahab offering kitesurfing, windsurfing, diving, and snorkeling.',
        'address' => 'Lighthouse, Dahab, South Sinai, Egypt',
        'license_number' => 'TSL-2020-003',
        'is_approved' => true,
        'created_at' => now()->subDays(50),
    ]);
    
    $company3 = App\Models\Company::create([
        'name' => 'New Horizon Adventures',
        'email' => 'info@newhorizonadv.com',
        'password' => bcrypt('company123'),
        'phone' => '+20693605678',
        'description' => 'New tour company specializing in adventure sports and extreme activities in the Red Sea region.',
        'address' => 'Naama Bay, Sharm El Sheikh, South Sinai, Egypt',
        'license_number' => 'TSL-2024-009',
        'is_approved' => false,
        'created_at' => now()->subDays(5),
    ]);
    
    echo "✅ Created 3 companies (2 approved, 1 pending)\n\n";
    
    // Create Trips
    echo "🗺️ Creating trips...\n";
    
    $trip1 = App\Models\Trip::create([
        'company_id' => $company1->id,
        'title' => 'Ras Mohammed National Park Snorkeling Adventure',
        'description' => 'Explore the breathtaking coral reefs and marine life at Ras Mohammed National Park. This full-day snorkeling trip includes multiple stops at the best coral gardens.',
        'destination' => 'Sharm El Sheikh',
        'price' => 75.00,
        'duration_days' => 1,
        'max_participants' => 25,
        'available_spots' => 25,
        'includes' => 'Hotel pickup & drop-off, snorkeling equipment, lunch, professional guide, entrance fees',
        'difficulty_level' => 'easy',
        'category' => 'water_sports',
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(5),
        'images' => json_encode(['/images/trips/ras-mohammed-1.jpg']),
        'is_active' => true,
    ]);
    
    $trip2 = App\Models\Trip::create([
        'company_id' => $company1->id,
        'title' => 'Tiran Island Diving Expedition',
        'description' => 'Experience world-class diving at Tiran Island with its famous four reefs. Perfect for certified divers seeking underwater adventures.',
        'destination' => 'Sharm El Sheikh',
        'price' => 95.00,
        'duration_days' => 1,
        'max_participants' => 16,
        'available_spots' => 16,
        'includes' => 'Boat transfer, diving equipment, 2 dives, lunch, certified dive guide',
        'difficulty_level' => 'intermediate',
        'category' => 'diving',
        'start_date' => now()->addDays(7),
        'end_date' => now()->addDays(7),
        'images' => json_encode(['/images/trips/tiran-diving-1.jpg']),
        'is_active' => true,
    ]);
    
    $trip3 = App\Models\Trip::create([
        'company_id' => $company2->id,
        'title' => 'Blue Hole Freediving Experience',
        'description' => 'Dive into the famous Blue Hole, one of the most renowned diving sites in the world. Perfect for experienced freedivers.',
        'destination' => 'Dahab',
        'price' => 110.00,
        'duration_days' => 1,
        'max_participants' => 8,
        'available_spots' => 8,
        'includes' => 'Professional freediving instructor, safety equipment, lunch, transportation',
        'difficulty_level' => 'challenging',
        'category' => 'diving',
        'start_date' => now()->addDays(8),
        'end_date' => now()->addDays(8),
        'images' => json_encode(['/images/trips/blue-hole-1.jpg']),
        'is_active' => true,
    ]);
    
    echo "✅ Created 3 exciting trips\n\n";
    
    // Create Bookings
    echo "📅 Creating bookings...\n";
    
    $regularUsers = App\Models\User::whereNotIn('email', ['admin@rahlety.com', 'superadmin@rahlety.com'])->get();
    
    for ($i = 0; $i < 10; $i++) {
        $user = $regularUsers->random();
        $trip = App\Models\Trip::inRandomOrder()->first();
        $agent = rand(1, 100) <= 30 ? App\Models\Agent::inRandomOrder()->first() : null;
        
        $participants = rand(1, 3);
        $totalPrice = $trip->price * $participants;
        
        $status = ['confirmed', 'pending', 'cancelled'][rand(0, 2)];
        if ($i < 7) $status = 'confirmed'; // Most bookings confirmed
        
        $booking = App\Models\Booking::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'company_id' => $trip->company_id,
            'agent_id' => $agent?->id,
            'reference_code' => 'RHL2025' . strtoupper(uniqid()),
            'status' => $status,
            'participants' => $participants,
            'price_per_person' => $trip->price,
            'total_price' => $totalPrice,
            'special_requirements' => '',
            'booked_at' => now()->subDays(rand(1, 30)),
            'created_at' => now()->subDays(rand(1, 30)),
        ]);
        
        // Create commission if agent involved and booking confirmed
        if ($agent && $status === 'confirmed') {
            $commissionAmount = ($totalPrice * 5) / 100; // 5% commission
            
            App\Models\Commission::create([
                'agent_id' => $agent->id,
                'booking_id' => $booking->id,
                'amount' => $commissionAmount,
                'paid' => rand(1, 100) <= 70, // 70% chance of being paid
                'paid_at' => rand(1, 100) <= 70 ? now()->subDays(rand(1, 15)) : null,
                'created_at' => $booking->created_at->addDays(1),
            ]);
        }
    }
    
    echo "✅ Created 10 bookings with commissions\n\n";
    
    // Create Reviews
    echo "⭐ Creating reviews...\n";
    
    $confirmedBookings = App\Models\Booking::where('status', 'confirmed')->get();
    
    foreach ($confirmedBookings->take(6) as $booking) {
        $rating = [5, 5, 4, 4, 3, 2][array_rand([5, 5, 4, 4, 3, 2])]; // Weighted towards positive
        
        $comments = [
            5 => "Absolutely incredible experience! The Red Sea is truly magical and the guides were fantastic.",
            4 => "Great experience overall! Small issues with timing but nothing major.",
            3 => "Decent trip but felt a bit rushed. Could use better time management.",
            2 => "Disappointing experience. Poor organization and delays.",
        ];
        
        App\Models\Review::create([
            'user_id' => $booking->user_id,
            'trip_id' => $booking->trip_id,
            'booking_id' => $booking->id,
            'rating' => $rating,
            'comment' => $comments[$rating] ?? 'Good experience overall.',
            'created_at' => $booking->created_at->addDays(rand(1, 7)),
        ]);
    }
    
    echo "✅ Created 6 reviews\n\n";
    
    // Display Summary
    echo "🎉 Manual seeding completed successfully!\n\n";
    echo "📊 Database Summary:\n";
    echo "==================\n";
    echo "👥 Users: " . App\Models\User::count() . " (including 2 admins)\n";
    echo "🤝 Agents: " . App\Models\Agent::count() . "\n";
    echo "🏢 Companies: " . App\Models\Company::count() . " (" . App\Models\Company::where('is_approved', true)->count() . " approved)\n";
    echo "🗺️ Trips: " . App\Models\Trip::count() . "\n";
    echo "📅 Bookings: " . App\Models\Booking::count() . " (" . App\Models\Booking::where('status', 'confirmed')->count() . " confirmed)\n";
    echo "⭐ Reviews: " . App\Models\Review::count() . " (avg: " . round(App\Models\Review::avg('rating'), 2) . "/5)\n";
    echo "💰 Commissions: " . App\Models\Commission::count() . "\n";
    echo "💵 Total Revenue: $" . number_format(App\Models\Booking::where('status', 'confirmed')->sum('total_price'), 2) . "\n\n";
    
    echo "🚀 Ready for testing!\n";
    echo "🎛️ Access admin dashboard: http://localhost:8000/admin/login\n";
    echo "🔑 Use admin@rahlety.com / admin123 to login\n\n";
    
} catch (Exception $e) {
    echo "❌ Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}