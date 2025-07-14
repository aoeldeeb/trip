<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Booking;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get confirmed bookings that are in the past (completed trips)
        $completedBookings = Booking::with(['user', 'trip'])
            ->where('status', 'confirmed')
            ->whereHas('trip', function($query) {
                $query->where('end_date', '<', now());
            })
            ->get();
        
        if ($completedBookings->isEmpty()) {
            $this->command->info('No completed bookings found. Reviews can only be created for finished trips.');
            return;
        }

        // Realistic review data with Egyptian tourism context
        $reviewTemplates = [
            // 5-star reviews
            5 => [
                "Absolutely incredible experience! The Red Sea is truly magical and the guides were fantastic.",
                "Best diving experience ever! The coral reefs were breathtaking and well-preserved.",
                "Perfect trip with amazing Bedouin hospitality. Highly recommend to everyone!",
                "Outstanding service from start to finish. The desert safari was unforgettable.",
                "Exceeded all expectations! Professional team and stunning locations.",
                "Amazing snorkeling spots with crystal clear water. Will definitely come back!",
                "Wonderful cultural experience with authentic local food and traditions.",
                "Perfect organization and safety measures. Felt completely safe throughout.",
                "The Mount Sinai sunrise was absolutely magical. Worth every moment!",
                "Incredible marine life and pristine coral reefs. A true underwater paradise.",
            ],
            // 4-star reviews  
            4 => [
                "Great experience overall! Small issues with timing but nothing major.",
                "Very good trip with knowledgeable guides. Weather could have been better.",
                "Enjoyed the adventure! Food was good and locations were beautiful.",
                "Solid tour with professional staff. Would recommend with minor reservations.",
                "Good value for money. Some equipment could be newer but still functional.",
                "Nice diving spots though a bit crowded. Guide was very experienced.",
                "Pleasant experience with friendly locals. Transportation was comfortable.",
                "Good variety of activities. Lunch was tasty and local.",
                "Interesting cultural insights. Could have been longer but still worthwhile.",
                "Well-organized trip with beautiful scenery. Minor language barriers.",
            ],
            // 3-star reviews
            3 => [
                "Decent trip but felt a bit rushed. Could use better time management.",
                "Average experience. Some parts were great, others not so much.",
                "Okay for the price. Equipment was basic but functional.",
                "Mixed feelings. Beautiful locations but service could improve.",
                "Not bad but expected more based on the description.",
                "Acceptable tour. Guide was friendly but not very informative.",
                "Standard experience. Nothing exceptional but nothing terrible either.",
                "Fair value. Some activities were better than others.",
                "Adequate service. Food was okay, locations were nice.",
                "Reasonable trip. Would consider other options next time.",
            ],
            // 2-star reviews
            2 => [
                "Disappointing experience. Poor organization and delays.",
                "Not worth the money. Equipment was old and unreliable.",
                "Many issues with scheduling and communication problems.",
                "Below expectations. Guide seemed inexperienced.",
                "Poor value for money. Several promised activities were cancelled.",
                "Frustrating experience with multiple delays and confusion.",
                "Service quality was poor. Food was not good.",
                "Had better experiences elsewhere. This was subpar.",
                "Too many problems to recommend. Safety concerns as well.",
                "Overpriced for what was delivered. Disappointing overall.",
            ],
            // 1-star reviews  
            1 => [
                "Terrible experience. Complete waste of money and time.",
                "Worst tour ever! Dangerous conditions and unprofessional staff.",
                "Absolutely awful. Nothing went as promised.",
                "Disaster from start to finish. Would not recommend to anyone.",
                "Completely unprofessional service. Safety was compromised.",
            ],
        ];

        // Generate reviews for a percentage of completed bookings (65% review rate)
        $reviewableBookings = $completedBookings->random(min(
            $completedBookings->count(), 
            (int)($completedBookings->count() * 0.65)
        ));

        foreach ($reviewableBookings as $booking) {
            // Weight the ratings towards positive (realistic for good tourism businesses)
            $ratingWeights = [
                5 => 40, // 40% 5-star
                4 => 30, // 30% 4-star  
                3 => 20, // 20% 3-star
                2 => 8,  // 8% 2-star
                1 => 2,  // 2% 1-star
            ];
            
            $random = rand(1, 100);
            $rating = 5; // default
            $cumulative = 0;
            
            foreach ($ratingWeights as $stars => $weight) {
                $cumulative += $weight;
                if ($random <= $cumulative) {
                    $rating = $stars;
                    break;
                }
            }
            
            // Select appropriate review template
            $reviewTexts = $reviewTemplates[$rating];
            $reviewText = $reviewTexts[array_rand($reviewTexts)];
            
            // Add trip-specific context occasionally
            $tripSpecificAdditions = [
                'diving' => [
                    5 => " The underwater visibility was excellent!",
                    4 => " Good diving conditions overall.",
                    3 => " Decent diving spots.",
                    2 => " Visibility could have been better.",
                    1 => " Poor diving conditions.",
                ],
                'water_sports' => [
                    5 => " Perfect weather conditions for water activities!",
                    4 => " Good conditions for water sports.",
                    3 => " Acceptable weather for activities.",
                    2 => " Weather wasn't ideal.",
                    1 => " Poor weather ruined the experience.",
                ],
                'cultural' => [
                    5 => " Learned so much about local culture and history!",
                    4 => " Interesting cultural insights.",
                    3 => " Some cultural information provided.",
                    2 => " Limited cultural content.",
                    1 => " No real cultural experience.",
                ],
                'adventure' => [
                    5 => " The adrenaline rush was incredible!",
                    4 => " Good adventure experience.",
                    3 => " Some exciting moments.",
                    2 => " Less adventurous than expected.",
                    1 => " Boring and poorly planned.",
                ],
            ];
            
            // Add specific context 30% of the time
            if (rand(1, 100) <= 30 && isset($tripSpecificAdditions[$booking->trip->category])) {
                $reviewText .= $tripSpecificAdditions[$booking->trip->category][$rating];
            }
            
            // Create the review
            Review::create([
                'user_id' => $booking->user_id,
                'trip_id' => $booking->trip_id,
                'booking_id' => $booking->id,
                'rating' => $rating,
                'comment' => $reviewText,
                'created_at' => $booking->updated_at->addDays(rand(1, 7)), // Review 1-7 days after trip
                'updated_at' => $booking->updated_at->addDays(rand(1, 7)),
            ]);
        }

        $totalReviews = Review::count();
        $averageRating = round(Review::avg('rating'), 2);
        $ratingDistribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = Review::where('rating', $i)->count();
        }
        
        $this->command->info("Created {$totalReviews} reviews:");
        $this->command->info("- Average rating: {$averageRating}/5");
        $this->command->info("- Rating distribution:");
        foreach ($ratingDistribution as $rating => $count) {
            $this->command->info("  {$rating} stars: {$count} reviews");
        }
    }
}