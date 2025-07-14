<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agent_id',
        'booking_id',
        'amount',
        'paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid' => 'boolean',
        ];
    }

    /**
     * Get the agent that earned this commission.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Get the booking that generated this commission.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope to get paid commissions.
     */
    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    /**
     * Scope to get unpaid commissions.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('paid', false);
    }

    /**
     * Scope to get commissions by agent.
     */
    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Mark commission as paid.
     */
    public function markAsPaid()
    {
        $this->update(['paid' => true]);
    }

    /**
     * Mark commission as unpaid.
     */
    public function markAsUnpaid()
    {
        $this->update(['paid' => false]);
    }
}
