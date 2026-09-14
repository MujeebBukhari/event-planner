<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Mail\EventBookingSubmitted;
use App\Models\ContactMessage;
use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Successful booking sends the booking notification.
     */
    public function test_successful_booking_sends_booking_notification(): void
    {
        Mail::fake();

        $response = $this->post(route('tickets.store'), [
            'name' => 'Michael Scott',
            'email' => 'michael@dundermifflin.com',
            'phone' => '555-0100',
            'event_type' => 'General Admission',
            'guest_count' => 2,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('tickets'));
        $response->assertSessionHas('success');

        Mail::assertSent(EventBookingSubmitted::class, function (EventBookingSubmitted $mail) {
            return $mail->hasTo(config('mail.admin_email')) &&
                   $mail->booking->name === 'Michael Scott' &&
                   $mail->booking->guest_count === 2;
        });
    }

    /**
     * 2. Booking email contains the correct booking information.
     */
    public function test_booking_email_contains_correct_booking_information(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Pam Beesly',
            'email' => 'pam@dundermifflin.com',
            'phone' => '(612) 555-0199',
            'event_type' => 'VIP Access',
            'guest_count' => 3,
            'total_amount' => 180.00,
            'payment_method' => 'apple_pay',
            'status' => 'pending',
        ]);

        $mailable = new EventBookingSubmitted($booking);
        $rendered = $mailable->render();

        $this->assertStringContainsString('Pam Beesly', $rendered);
        $this->assertStringContainsString('pam@dundermifflin.com', $rendered);
        $this->assertStringContainsString('(612) 555-0199', $rendered);
        $this->assertStringContainsString('VIP Access', $rendered);
        $this->assertStringContainsString('3', $rendered);
        $this->assertStringContainsString('$180.00', $rendered);
        $this->assertStringContainsString('APPLE_PAY', $rendered);
        $this->assertStringContainsString('Pending', $rendered);
    }

    /**
     * 3. Sensitive card information is not included in the email.
     */
    public function test_sensitive_card_information_is_not_included_in_email(): void
    {
        $booking = EventBooking::factory()->create([
            'name' => 'Jim Halpert',
            'email' => 'jim@dundermifflin.com',
            'payment_method' => 'card',
        ]);

        $mailable = new EventBookingSubmitted($booking);
        $rendered = $mailable->render();

        $this->assertStringNotContainsString('4242', $rendered);
        $this->assertStringNotContainsString('card_number', $rendered);
        $this->assertStringNotContainsString('card_cvc', $rendered);
        $this->assertStringNotContainsString('card_expiry', $rendered);
        $this->assertStringNotContainsString('billing_zip', $rendered);
    }

    /**
     * 4. Successful contact submission sends the contact notification.
     */
    public function test_successful_contact_submission_sends_contact_notification(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Dwight Schrute',
            'email' => 'dwight@schrutefarms.com',
            'phone' => '555-0198',
            'subject' => 'Planning an Event',
            'message' => 'Need event production for the annual Beet Festival.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail) {
            return $mail->hasTo(config('mail.admin_email')) &&
                   $mail->contactMessage->name === 'Dwight Schrute' &&
                   $mail->contactMessage->subject === 'Planning an Event';
        });
    }

    /**
     * 5. Contact email contains the correct contact information.
     */
    public function test_contact_email_contains_correct_contact_information(): void
    {
        $message = ContactMessage::factory()->create([
            'name' => 'Angela Martin',
            'email' => 'angela@dundermifflin.com',
            'phone' => '555-0155',
            'subject' => 'Building a Personal Brand',
            'message' => 'Party Planning Committee branding strategy inquiry.',
        ]);

        $mailable = new ContactMessageReceived($message);
        $rendered = $mailable->render();

        $this->assertStringContainsString('Angela Martin', $rendered);
        $this->assertStringContainsString('angela@dundermifflin.com', $rendered);
        $this->assertStringContainsString('555-0155', $rendered);
        $this->assertStringContainsString('Building a Personal Brand', $rendered);
        $this->assertStringContainsString('Party Planning Committee branding strategy inquiry.', $rendered);
    }

    /**
     * 6. Database records are still created correctly.
     */
    public function test_database_records_are_still_created_correctly(): void
    {
        Mail::fake();

        $this->post(route('tickets.store'), [
            'name' => 'Stanley Hudson',
            'email' => 'stanley@dundermifflin.com',
            'event_type' => 'General Admission',
            'guest_count' => 1,
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('event_bookings', [
            'name' => 'Stanley Hudson',
            'email' => 'stanley@dundermifflin.com',
            'total_amount' => 25.00,
        ]);

        $this->post(route('contact.store'), [
            'name' => 'Phyllis Vance',
            'email' => 'phyllis@vancerefrigeration.com',
            'subject' => 'Growing a Business',
            'message' => 'Inquiry for Vance Refrigeration rebrand.',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Phyllis Vance',
            'email' => 'phyllis@vancerefrigeration.com',
            'status' => 'unread',
        ]);
    }

    /**
     * 7. Mail writes to log driver during local development.
     */
    public function test_mail_writes_to_log_driver(): void
    {
        config(['mail.default' => 'log']);

        $booking = EventBooking::factory()->create([
            'name' => 'Log Verification Customer',
            'email' => 'logtest@example.com',
            'event_type' => 'VIP Access',
            'guest_count' => 2,
            'total_amount' => 120.00,
            'payment_method' => 'card',
        ]);

        Mail::to(config('mail.admin_email'))->send(new EventBookingSubmitted($booking));

        $logPath = storage_path('logs/laravel.log');
        $this->assertFileExists($logPath);
        $logContent = file_get_contents($logPath);
        $this->assertStringContainsString('New Ticket Booking Received', $logContent);
        $this->assertStringContainsString('Log Verification Customer', $logContent);
        $this->assertStringContainsString('admin@rkvibes.com', $logContent);
        $this->assertStringNotContainsString('4242424242424242', $logContent);
    }
}
