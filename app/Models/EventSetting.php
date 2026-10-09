<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tag',
        'title',
        'date_location',
        'doors_time',
        'general_admission_price',
        'vip_access_price',
        'general_admission_desc',
        'vip_access_desc',
        'cta_text',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'general_admission_price' => 'float',
        'vip_access_price' => 'float',
    ];

    /**
     * Retrieve the current featured event settings, or create defaults.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'tag' => 'Upcoming Event',
                'title' => 'RK Vibes Presents — Fall Gathering 2026',
                'date_location' => 'Minneapolis, MN · Oct 2026',
                'doors_time' => 'Doors 6:00 PM',
                'general_admission_price' => 25.00,
                'vip_access_price' => 60.00,
                'general_admission_desc' => 'Standing room, full event access',
                'vip_access_desc' => 'Front section, early entry, meet & greet',
                'cta_text' => 'Book Now — Secure Checkout',
                'note' => 'Paid direct via Stripe — no third-party ticketing fees',
            ]
        );
    }

    /**
     * Get ticket prices indexed by tier name.
     *
     * @return array<string, float>
     */
    public function ticketPrices(): array
    {
        return [
            'General Admission' => (float) $this->general_admission_price,
            'VIP Access' => (float) $this->vip_access_price,
        ];
    }

    /**
     * Formatted string for General Admission price (e.g. "$25" or "$25.50").
     */
    public function getGaPriceDisplayAttribute(): string
    {
        $price = (float) $this->general_admission_price;

        return (floor($price) === $price) ? '$'.((int) $price) : '$'.number_format($price, 2);
    }

    /**
     * Formatted string for VIP Access price (e.g. "$60" or "$60.00").
     */
    public function getVipPriceDisplayAttribute(): string
    {
        $price = (float) $this->vip_access_price;

        return (floor($price) === $price) ? '$'.((int) $price) : '$'.number_format($price, 2);
    }
}
