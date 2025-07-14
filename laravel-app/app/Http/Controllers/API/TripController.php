<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTripRequest;
use App\Models\Trip;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    /**
     * Display a listing of trips with filters.
     */
    public function index(Request $request)
    {
        $query = Trip::with(['company:id,name', 'reviews'])
            ->active()
            ->upcoming();

        // Apply filters
        if ($request->location) {
            $query->byLocation($request->location);
        }

        if ($request->date) {
            $query->where('date', '>=', $request->date);
        }

        if ($request->start_date && $request->end_date) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        if ($request->min_price || $request->max_price) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }

        // Sort by date or price
        $sortBy = $request->sort_by ?? 'date';
        $sortOrder = $request->sort_order ?? 'asc';
        
        if (in_array($sortBy, ['date', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $trips = $query->paginate(15);

        // Add computed attributes
        $trips->getCollection()->transform(function ($trip) {
            $trip->available_capacity = $trip->available_capacity;
            $trip->average_rating = $trip->average_rating;
            $trip->reviews_count = $trip->reviews_count;
            return $trip;
        });

        return response()->json([
            'trips' => $trips,
            'message' => 'Trips retrieved successfully'
        ]);
    }

    /**
     * Display the specified trip.
     */
    public function show($id)
    {
        $trip = Trip::with(['company:id,name,phone', 'reviews.user:id,name'])
            ->findOrFail($id);

        $trip->available_capacity = $trip->available_capacity;
        $trip->average_rating = $trip->average_rating;
        $trip->reviews_count = $trip->reviews_count;

        return response()->json([
            'trip' => $trip,
            'message' => 'Trip retrieved successfully'
        ]);
    }

    /**
     * Store a newly created trip.
     */
    public function store(CreateTripRequest $request)
    {
        $user = $request->user();
        
        if (!$user instanceof Company) {
            return response()->json([
                'message' => 'Only companies can create trips'
            ], 403);
        }

        if (!$user->is_approved) {
            return response()->json([
                'message' => 'Company must be approved to create trips'
            ], 403);
        }

        $tripData = $request->validated();
        $tripData['company_id'] = $user->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $tripData['image'] = $request->file('image')->store('trips', 'public');
        }

        $trip = Trip::create($tripData);
        $trip->load('company:id,name');

        return response()->json([
            'trip' => $trip,
            'message' => 'Trip created successfully'
        ], 201);
    }

    /**
     * Update the specified trip.
     */
    public function update(CreateTripRequest $request, $id)
    {
        $user = $request->user();
        $trip = Trip::findOrFail($id);

        if (!$user instanceof Company || $trip->company_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized to update this trip'
            ], 403);
        }

        $tripData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($trip->image) {
                Storage::disk('public')->delete($trip->image);
            }
            $tripData['image'] = $request->file('image')->store('trips', 'public');
        }

        $trip->update($tripData);
        $trip->load('company:id,name');

        return response()->json([
            'trip' => $trip,
            'message' => 'Trip updated successfully'
        ]);
    }

    /**
     * Remove the specified trip.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $trip = Trip::findOrFail($id);

        if (!$user instanceof Company || $trip->company_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized to delete this trip'
            ], 403);
        }

        // Check if there are confirmed bookings
        $confirmedBookings = $trip->bookings()->confirmed()->count();
        if ($confirmedBookings > 0) {
            return response()->json([
                'message' => 'Cannot delete trip with confirmed bookings'
            ], 422);
        }

        // Delete image if exists
        if ($trip->image) {
            Storage::disk('public')->delete($trip->image);
        }

        $trip->delete();

        return response()->json([
            'message' => 'Trip deleted successfully'
        ]);
    }

    /**
     * Get trips for the authenticated company.
     */
    public function myTrips(Request $request)
    {
        $user = $request->user();
        
        if (!$user instanceof Company) {
            return response()->json([
                'message' => 'Only companies can access this endpoint'
            ], 403);
        }

        $trips = Trip::where('company_id', $user->id)
            ->with(['bookings' => function($query) {
                $query->selectRaw('trip_id, count(*) as total_bookings, sum(persons) as total_persons')
                    ->groupBy('trip_id');
            }])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return response()->json([
            'trips' => $trips,
            'message' => 'Company trips retrieved successfully'
        ]);
    }
}
