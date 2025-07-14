<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Company;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::where('is_approved', true)->get();
        
        if ($companies->isEmpty()) {
            $this->command->error('No approved companies found. Run CompanySeeder first.');
            return;
        }

        $trips = [
            // Sharm El Sheikh trips
            [
                'title' => 'Ras Mohammed National Park Snorkeling Adventure',
                'description' => 'Explore the breathtaking coral reefs and marine life at Ras Mohammed National Park. This full-day snorkeling trip includes multiple stops at the best coral gardens and a visit to the magical lake.',
                'destination' => 'Sharm El Sheikh',
                'price' => 75.00,
                'duration_days' => 1,
                'max_participants' => 25,
                'includes' => 'Hotel pickup & drop-off, snorkeling equipment, lunch, professional guide, entrance fees',
                'difficulty_level' => 'easy',
                'category' => 'water_sports',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(5),
                'images' => json_encode(['/images/trips/ras-mohammed-1.jpg', '/images/trips/ras-mohammed-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Tiran Island Diving Expedition',
                'description' => 'Experience world-class diving at Tiran Island with its famous four reefs: Jackson, Woodhouse, Thomas, and Gordon. Perfect for certified divers seeking underwater adventures.',
                'destination' => 'Sharm El Sheikh',
                'price' => 95.00,
                'duration_days' => 1,
                'max_participants' => 16,
                'includes' => 'Boat transfer, diving equipment, 2 dives, lunch, certified dive guide',
                'difficulty_level' => 'intermediate',
                'category' => 'diving',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7),
                'images' => json_encode(['/images/trips/tiran-diving-1.jpg', '/images/trips/tiran-diving-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Mount Sinai Sunrise Trek',
                'description' => 'Climb the sacred Mount Sinai (Mount Moses) under the stars and witness one of the most spectacular sunrises in the world. Visit Saint Catherine Monastery afterward.',
                'destination' => 'Sharm El Sheikh',
                'price' => 85.00,
                'duration_days' => 1,
                'max_participants' => 30,
                'includes' => 'Transportation, Bedouin guide, flashlight, breakfast, monastery entrance',
                'difficulty_level' => 'challenging',
                'category' => 'adventure',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(10),
                'images' => json_encode(['/images/trips/mount-sinai-1.jpg', '/images/trips/mount-sinai-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Colored Canyon & White Canyon Safari',
                'description' => 'Explore the stunning Colored Canyon with its narrow passages and amazing rock formations, then visit the unique White Canyon for a complete desert adventure.',
                'destination' => 'Sharm El Sheikh',
                'price' => 65.00,
                'duration_days' => 1,
                'max_participants' => 20,
                'includes' => '4WD vehicle, professional driver, lunch, water, entrance fees',
                'difficulty_level' => 'moderate',
                'category' => 'desert_safari',
                'start_date' => now()->addDays(12),
                'end_date' => now()->addDays(12),
                'images' => json_encode(['/images/trips/colored-canyon-1.jpg', '/images/trips/colored-canyon-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Luxury Desert Camp Experience',
                'description' => 'Overnight luxury camping in the Sinai desert with traditional Bedouin hospitality, camel riding, stargazing, and authentic meals around the campfire.',
                'destination' => 'Sharm El Sheikh',
                'price' => 120.00,
                'duration_days' => 2,
                'max_participants' => 15,
                'includes' => 'Luxury tent accommodation, all meals, camel ride, campfire, transportation',
                'difficulty_level' => 'easy',
                'category' => 'cultural',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(16),
                'images' => json_encode(['/images/trips/desert-camp-1.jpg', '/images/trips/desert-camp-2.jpg']),
                'is_active' => true,
            ],

            // Dahab trips
            [
                'title' => 'Blue Hole Freediving Experience',
                'description' => 'Dive into the famous Blue Hole, one of the most renowned diving sites in the world. Perfect for experienced freedivers and those seeking the ultimate underwater challenge.',
                'destination' => 'Dahab',
                'price' => 110.00,
                'duration_days' => 1,
                'max_participants' => 8,
                'includes' => 'Professional freediving instructor, safety equipment, lunch, transportation',
                'difficulty_level' => 'challenging',
                'category' => 'diving',
                'start_date' => now()->addDays(8),
                'end_date' => now()->addDays(8),
                'images' => json_encode(['/images/trips/blue-hole-1.jpg', '/images/trips/blue-hole-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Kitesurfing Lessons & Adventure',
                'description' => 'Learn kitesurfing in the perfect windy conditions of Dahab bay. Suitable for beginners and advanced riders with modern equipment and certified instructors.',
                'destination' => 'Dahab',
                'price' => 90.00,
                'duration_days' => 1,
                'max_participants' => 6,
                'includes' => 'Equipment rental, certified instructor, lunch, safety briefing',
                'difficulty_level' => 'moderate',
                'category' => 'water_sports',
                'start_date' => now()->addDays(6),
                'end_date' => now()->addDays(6),
                'images' => json_encode(['/images/trips/kitesurfing-1.jpg', '/images/trips/kitesurfing-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Canyon Adventure & Oasis Discovery',
                'description' => 'Explore hidden canyons and discover secret oases in the Sinai mountains. A perfect combination of hiking, swimming, and natural beauty exploration.',
                'destination' => 'Dahab',
                'price' => 70.00,
                'duration_days' => 1,
                'max_participants' => 12,
                'includes' => 'Transportation, hiking guide, lunch, swimming stops, water',
                'difficulty_level' => 'moderate',
                'category' => 'adventure',
                'start_date' => now()->addDays(9),
                'end_date' => now()->addDays(9),
                'images' => json_encode(['/images/trips/canyon-oasis-1.jpg', '/images/trips/canyon-oasis-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Camel Trek to Bedouin Village',
                'description' => 'Authentic camel trekking experience to a traditional Bedouin village. Learn about desert life, enjoy traditional tea, and experience genuine hospitality.',
                'destination' => 'Dahab',
                'price' => 55.00,
                'duration_days' => 1,
                'max_participants' => 18,
                'includes' => 'Camel ride, Bedouin guide, traditional lunch, tea ceremony, transportation',
                'difficulty_level' => 'easy',
                'category' => 'cultural',
                'start_date' => now()->addDays(11),
                'end_date' => now()->addDays(11),
                'images' => json_encode(['/images/trips/camel-trek-1.jpg', '/images/trips/camel-trek-2.jpg']),
                'is_active' => true,
            ],

            // Nuweiba trips
            [
                'title' => 'Coral Beach Snorkeling Paradise',
                'description' => 'Discover the pristine coral reefs of Nuweiba with crystal clear waters and abundant marine life. Perfect for families and snorkeling enthusiasts.',
                'destination' => 'Nuweiba',
                'price' => 60.00,
                'duration_days' => 1,
                'max_participants' => 20,
                'includes' => 'Snorkeling equipment, boat trip, lunch, soft drinks, guide',
                'difficulty_level' => 'easy',
                'category' => 'water_sports',
                'start_date' => now()->addDays(13),
                'end_date' => now()->addDays(13),
                'images' => json_encode(['/images/trips/nuweiba-coral-1.jpg', '/images/trips/nuweiba-coral-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Fjord Bay Kayaking Adventure',
                'description' => 'Paddle through the stunning Fjord Bay in crystal clear waters surrounded by dramatic mountains. A peaceful and scenic water adventure.',
                'destination' => 'Nuweiba',
                'price' => 80.00,
                'duration_days' => 1,
                'max_participants' => 10,
                'includes' => 'Kayak equipment, safety gear, professional guide, lunch, water',
                'difficulty_level' => 'moderate',
                'category' => 'water_sports',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(14),
                'images' => json_encode(['/images/trips/fjord-kayak-1.jpg', '/images/trips/fjord-kayak-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Castle Zaman Historical Tour',
                'description' => 'Visit the magnificent Castle Zaman, a medieval-style castle overlooking the Red Sea. Learn about local history and enjoy panoramic views.',
                'destination' => 'Nuweiba',
                'price' => 45.00,
                'duration_days' => 1,
                'max_participants' => 25,
                'includes' => 'Transportation, entrance fees, guided tour, refreshments',
                'difficulty_level' => 'easy',
                'category' => 'cultural',
                'start_date' => now()->addDays(16),
                'end_date' => now()->addDays(16),
                'images' => json_encode(['/images/trips/castle-zaman-1.jpg', '/images/trips/castle-zaman-2.jpg']),
                'is_active' => true,
            ],

            // Special multi-day trips
            [
                'title' => 'Ultimate Sinai Peninsula Discovery',
                'description' => 'Complete 5-day exploration of the Sinai Peninsula including all three destinations: Sharm El Sheikh, Dahab, and Nuweiba. The ultimate Red Sea experience.',
                'destination' => 'Multiple',
                'price' => 450.00,
                'duration_days' => 5,
                'max_participants' => 12,
                'includes' => 'All accommodations, meals, transportation, activities, professional guide',
                'difficulty_level' => 'moderate',
                'category' => 'adventure',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(24),
                'images' => json_encode(['/images/trips/sinai-discovery-1.jpg', '/images/trips/sinai-discovery-2.jpg']),
                'is_active' => true,
            ],
            [
                'title' => 'Diving Certification Course',
                'description' => 'Get your PADI Open Water certification in the beautiful Red Sea. Professional instruction, excellent diving conditions, and lifetime underwater adventure access.',
                'destination' => 'Sharm El Sheikh',
                'price' => 350.00,
                'duration_days' => 4,
                'max_participants' => 8,
                'includes' => 'PADI certification, all equipment, instruction, certification materials, dives',
                'difficulty_level' => 'moderate',
                'category' => 'diving',
                'start_date' => now()->addDays(25),
                'end_date' => now()->addDays(28),
                'images' => json_encode(['/images/trips/padi-course-1.jpg', '/images/trips/padi-course-2.jpg']),
                'is_active' => true,
            ],
        ];

        foreach ($trips as $index => $tripData) {
            $company = $companies->random();
            
            Trip::create([
                'company_id' => $company->id,
                'title' => $tripData['title'],
                'description' => $tripData['description'],
                'destination' => $tripData['destination'],
                'price' => $tripData['price'],
                'duration_days' => $tripData['duration_days'],
                'max_participants' => $tripData['max_participants'],
                'available_spots' => $tripData['max_participants'],
                'includes' => $tripData['includes'],
                'difficulty_level' => $tripData['difficulty_level'],
                'category' => $tripData['category'],
                'start_date' => $tripData['start_date'],
                'end_date' => $tripData['end_date'],
                'images' => $tripData['images'],
                'is_active' => $tripData['is_active'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $this->command->info('Created ' . count($trips) . ' exciting trips across Sharm El Sheikh, Dahab, and Nuweiba');
    }
}