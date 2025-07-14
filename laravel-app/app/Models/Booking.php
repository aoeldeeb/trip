<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'trip_id',
        'company_id',
        'agent_id',
        'persons',
        'total_price',
        'status',
        'reference_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->reference_code)) {
                $booking->reference_code = 'BK-' . Str::upper(Str::random(8));
            }
        });

        static::created(function ($booking) {
            // Create commission if booked through an agent
            if ($booking->agent_id) {
                $commissionRate = config('rahlety.commission_rate', 0.05); // 5% default
                $commissionAmount = $booking->total_price * $commissionRate;

                Commission::create([
                    'agent_id' => $booking->agent_id,
                    'booking_id' => $booking->id,
                    'amount' => $commissionAmount,
                ]);

                // Update agent's total commission
                $booking->agent->increment('total_commission', $commissionAmount);
            }
        });
    }

    /**
     * Get the user that made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trip being booked.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the company that owns the trip.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the agent that referred this booking.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Get the commission for this booking.
     */
    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    /**
     * Scope to get bookings by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to get pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->trip->date >= now()->addHours(24);
    }

    /**
     * Cancel the booking.
     */
    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => 'cancelled']);
            
            // Reverse commission if there was one
            if ($this->commission) {
                $this->agent->decrement('total_commission', $this->commission->amount);
                $this->commission->delete();
            }
            
            return true;
        }
        
        return false;
    }
}
