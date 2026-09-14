<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSetupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test EventBooking model creation and defaults.
     */
    public function test_event_booking_can_be_created(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'event_type' => 'VIP Access',
            'guest_count' => 2,
            'total_amount' => 120.00,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('event_bookings', [
            'id' => $booking->id,
            'email' => 'john@example.com',
            'status' => 'pending',
            'guest_count' => 2,
        ]);

        $this->assertEquals('John Doe', $booking->name);
        $this->assertEquals(2, $booking->guest_count);
    }

    /**
     * Test ContactMessage model creation and defaults.
     */
    public function test_contact_message_can_be_created(): void
    {
        $message = ContactMessage::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '555-5678',
            'subject' => 'Planning an Event',
            'message' => 'Interested in event production.',
            'status' => 'unread',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'email' => 'jane@example.com',
            'subject' => 'Planning an Event',
            'status' => 'unread',
        ]);

        $this->assertEquals('Jane Smith', $message->name);
    }
}
