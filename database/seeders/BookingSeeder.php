<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Trip;
use App\Models\Agent;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNotIn('email', ['admin@rahlety.com', 'superadmin@rahlety.com'])->get();
        $trips = Trip::where('is_active', true)->get();
        $agents = Agent::all();
        
        if ($users->isEmpty() || $trips->isEmpty()) {
            $this->command->error('Users or trips not found. Run UserSeeder and TripSeeder first.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'cancelled'];
        $statusWeights = ['pending' => 20, 'confirmed' => 65, 'cancelled' => 15]; // percentage distribution
        
        // Generate bookings for the past 30 days
        for ($i = 0; $i < 45; $i++) {
            $user = $users->random();
            $trip = $trips->random();
            
            // Determine if this booking has an agent (30% chance)
            $agent = rand(1, 100) <= 30 ? $agents->random() : null;
            
            // Generate random number of participants (1-4 for families/groups)
            $participants = rand(1, min(4, $trip->available_spots));
            
            // Calculate pricing
            $pricePerPerson = $trip->price;
            $totalPrice = $pricePerPerson * $participants;
            
            // Randomly assign status based on weights
            $randomWeight = rand(1, 100);
            if ($randomWeight <= $statusWeights['cancelled']) {
                $status = 'cancelled';
            } elseif ($randomWeight <= $statusWeights['cancelled'] + $statusWeights['pending']) {
                $status = 'pending';
            } else {
                $status = 'confirmed';
            }
            
            // Generate reference code
            do {
                $referenceCode = 'RHL' . date('Y') . strtoupper(Str::random(6));
            } while (Booking::where('reference_code', $referenceCode)->exists());
            
            // Random booking date in the past 30 days
            $bookingDate = now()->subDays(rand(1, 30));
            
            // Special notes for some bookings
            $specialNotes = [
                'Vegetarian meals requested',
                'Celebrating anniversary',
                'First time diving',
                'Group booking - corporate event',
                'Honeymoon trip',
                'Birthday celebration',
                'Family with young children',
                'Photography tour',
                '',
                '',
                '', // Empty notes for most bookings
            ];
            
            Booking::create([
                'user_id' => $user->id,
                'trip_id' => $trip->id,
                'company_id' => $trip->company_id,
                'agent_id' => $agent?->id,
                'reference_code' => $referenceCode,
                'status' => $status,
                'participants' => $participants,
                'price_per_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'special_requirements' => $specialNotes[array_rand($specialNotes)],
                'booked_at' => $bookingDate,
                'created_at' => $bookingDate,
                'updated_at' => $bookingDate->copy()->addDays(rand(0, 3)),
            ]);
            
            // Update trip available spots for confirmed bookings
            if ($status === 'confirmed') {
                $trip->decrement('available_spots', $participants);
            }
        }

        // Create some future bookings (upcoming trips)
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $futureTrips = $trips->where('start_date', '>', now());
            
            if ($futureTrips->isEmpty()) {
                continue;
            }
            
            $trip = $futureTrips->random();
            $agent = rand(1, 100) <= 25 ? $agents->random() : null;
            $participants = rand(1, min(3, $trip->available_spots));
            
            $pricePerPerson = $trip->price;
            $totalPrice = $pricePerPerson * $participants;
            
            // Future bookings are mostly confirmed or pending
            $status = rand(1, 100) <= 85 ? 'confirmed' : 'pending';
            
            do {
                $referenceCode = 'RHL' . date('Y') . strtoupper(Str::random(6));
            } while (Booking::where('reference_code', $referenceCode)->exists());
            
            $bookingDate = now()->subDays(rand(1, 7)); // Booked recently for future trips
            
            Booking::create([
                'user_id' => $user->id,
                'trip_id' => $trip->id,
                'company_id' => $trip->company_id,
                'agent_id' => $agent?->id,
                'reference_code' => $referenceCode,
                'status' => $status,
                'participants' => $participants,
                'price_per_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'special_requirements' => '',
                'booked_at' => $bookingDate,
                'created_at' => $bookingDate,
                'updated_at' => $bookingDate,
            ]);
            
            if ($status === 'confirmed') {
                $trip->decrement('available_spots', $participants);
            }
        }

        // Create some bookings for today (recent activity)
        for ($i = 0; $i < 5; $i++) {
            $user = $users->random();
            $trip = $trips->random();
            $agent = rand(1, 100) <= 35 ? $agents->random() : null;
            $participants = rand(1, min(2, $trip->available_spots));
            
            $pricePerPerson = $trip->price;
            $totalPrice = $pricePerPerson * $participants;
            
            do {
                $referenceCode = 'RHL' . date('Y') . strtoupper(Str::random(6));
            } while (Booking::where('reference_code', $referenceCode)->exists());
            
            Booking::create([
                'user_id' => $user->id,
                'trip_id' => $trip->id,
                'company_id' => $trip->company_id,
                'agent_id' => $agent?->id,
                'reference_code' => $referenceCode,
                'status' => 'pending', // New bookings start as pending
                'participants' => $participants,
                'price_per_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'special_requirements' => '',
                'booked_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        
        $this->command->info("Created {$totalBookings} bookings:");
        $this->command->info("- Confirmed: {$confirmedBookings}");
        $this->command->info("- Pending: {$pendingBookings}");
        $this->command->info("- Cancelled: {$cancelledBookings}");
        $this->command->info("- Total revenue: $" . number_format($totalRevenue, 2));
    }
}