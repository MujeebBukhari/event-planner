<?php

namespace App\Models;

use Database\Factories\EventBookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    /** @use HasFactory<EventBookingFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_bookings';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'event_type',
        'event_date',
        'guest_count',
        'total_amount',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'paid_at',
        'message',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'guest_count' => 'integer',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Determine if the booking has been paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Mark the booking as paid and confirmed.
     */
    public function markAsPaid(?string $paymentIntentId = null, ?string $paymentMethod = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'stripe_payment_intent_id' => $paymentIntentId ?? $this->stripe_payment_intent_id,
            'payment_method' => $paymentMethod ?? $this->payment_method ?? 'stripe',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark the payment as failed.
     */
    public function markAsFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);
    }
}
