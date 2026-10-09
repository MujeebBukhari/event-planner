<?php

namespace Database\Factories;

use App\Models\EventBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventBooking>
 */
class EventBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'event_type' => fake()->randomElement(['General Admission', 'VIP Access', 'Corporate Gathering', 'Concert Night']),
            'event_date' => fake()->dateTimeBetween('+1 week', '+6 months')->format('Y-m-d'),
            'guest_count' => fake()->numberBetween(1, 10),
            'total_amount' => fake()->randomElement([25.00, 50.00, 60.00, 120.00]),
            'message' => fake()->sentence(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'card',
        ];
    }

    /**
     * Indicate that the booking is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'paid_at' => now(),
            'stripe_session_id' => 'cs_test_'.fake()->uuid(),
            'stripe_payment_intent_id' => 'pi_test_'.fake()->uuid(),
        ]);
    }

    /**
     * Indicate that the booking payment failed.
     */
    public function paymentFailed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'failed',
            'status' => 'pending',
        ]);
    }
}
