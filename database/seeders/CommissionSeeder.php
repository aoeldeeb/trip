<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\Booking;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get confirmed bookings that have agents assigned
        $bookingsWithAgents = Booking::with(['agent', 'trip'])
            ->where('status', 'confirmed')
            ->whereNotNull('agent_id')
            ->get();
        
        if ($bookingsWithAgents->isEmpty()) {
            $this->command->info('No confirmed bookings with agents found. Run BookingSeeder first.');
            return;
        }

        foreach ($bookingsWithAgents as $booking) {
            // Calculate commission amount (5% of total booking price)
            $commissionRate = config('rahlety.commission_rate', 5.0);
            $commissionAmount = ($booking->total_price * $commissionRate) / 100;
            
            // Determine payment status
            // Recent bookings (last 30 days) are more likely to be unpaid
            // Older bookings are more likely to be paid
            $daysSinceBooking = now()->diffInDays($booking->created_at);
            
            if ($daysSinceBooking <= 7) {
                // Very recent bookings - mostly unpaid (80% unpaid)
                $isPaid = rand(1, 100) <= 20;
            } elseif ($daysSinceBooking <= 30) {
                // Recent bookings - mixed (50% unpaid)
                $isPaid = rand(1, 100) <= 50;
            } else {
                // Older bookings - mostly paid (85% paid)
                $isPaid = rand(1, 100) <= 85;
            }
            
            // Payment date logic
            $paidAt = null;
            if ($isPaid) {
                // Payment typically happens 7-30 days after the booking
                $paymentDaysAfterBooking = rand(7, min(30, $daysSinceBooking));
                $paidAt = $booking->created_at->addDays($paymentDaysAfterBooking);
            }
            
            Commission::create([
                'agent_id' => $booking->agent_id,
                'booking_id' => $booking->id,
                'amount' => round($commissionAmount, 2),
                'paid' => $isPaid,
                'paid_at' => $paidAt,
                'created_at' => $booking->created_at->addDays(1), // Commission calculated day after booking
                'updated_at' => $paidAt ?: $booking->created_at->addDays(1),
            ]);
            
            // Update agent's total earned amount
            if ($isPaid) {
                $booking->agent->increment('total_earned', $commissionAmount);
            }
        }

        // Generate summary statistics
        $totalCommissions = Commission::count();
        $paidCommissions = Commission::where('paid', true)->count();
        $unpaidCommissions = Commission::where('paid', false)->count();
        $totalPaidAmount = Commission::where('paid', true)->sum('amount');
        $totalUnpaidAmount = Commission::where('paid', false)->sum('amount');
        $totalCommissionAmount = Commission::sum('amount');
        
        // Update agent total_earned for any discrepancies
        $agents = \App\Models\Agent::all();
        foreach ($agents as $agent) {
            $actualEarned = Commission::where('agent_id', $agent->id)
                ->where('paid', true)
                ->sum('amount');
            $agent->update(['total_earned' => $actualEarned]);
        }
        
        $this->command->info("Created {$totalCommissions} commission records:");
        $this->command->info("- Paid: {$paidCommissions} (\$" . number_format($totalPaidAmount, 2) . ")");
        $this->command->info("- Unpaid: {$unpaidCommissions} (\$" . number_format($totalUnpaidAmount, 2) . ")");
        $this->command->info("- Total commission amount: \$" . number_format($totalCommissionAmount, 2));
        $this->command->info("- Average commission: \$" . number_format($totalCommissionAmount / $totalCommissions, 2));
    }
}