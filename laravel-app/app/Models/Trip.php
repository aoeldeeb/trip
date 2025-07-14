<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'price',
        'included',
        'excluded',
        'capacity',
        'date',
        'time',
        'location',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'time' => 'datetime:H:i',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the company that owns the trip.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the bookings for the trip.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the trip.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope to get active trips.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get upcoming trips.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today());
    }

    /**
     * Scope to filter by location.
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        }
        return $query->where('date', '>=', $startDate);
    }

    /**
     * Scope to filter by price range.
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice = null)
    {
        if ($maxPrice) {
            return $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
        return $query->where('price', '>=', $minPrice);
    }

    /**
     * Get the available capacity for this trip.
     */
    public function getAvailableCapacityAttribute()
    {
        $bookedPersons = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->sum('persons');
        
        return $this->capacity - $bookedPersons;
    }

    /**
     * Check if the trip is fully booked.
     */
    public function isFullyBooked()
    {
        return $this->available_capacity <= 0;
    }

    /**
     * Get the average rating for this trip.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Get the total number of reviews.
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
