<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Agent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'referral_code',
        'total_commission',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'total_commission' => 'decimal:2',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($agent) {
            if (empty($agent->referral_code)) {
                $agent->referral_code = 'AGT-' . Str::upper(Str::random(8));
            }
        });
    }

    /**
     * Get the users referred by this agent.
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the commissions earned by this agent.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get the bookings made through this agent.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Calculate unpaid commissions.
     */
    public function unpaidCommissions()
    {
        return $this->commissions()->where('paid', false)->sum('amount');
    }

    /**
     * Calculate paid commissions.
     */
    public function paidCommissions()
    {
        return $this->commissions()->where('paid', true)->sum('amount');
    }
}
