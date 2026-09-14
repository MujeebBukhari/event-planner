<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Valid contact message is stored.
     */
    public function test_valid_contact_message_is_stored(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'phone' => '(612) 555-0144',
            'subject' => 'Growing a Business',
            'message' => 'Looking for marketing and strategy advice.',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'phone' => '(612) 555-0144',
            'subject' => 'Growing a Business',
            'message' => 'Looking for marketing and strategy advice.',
            'status' => 'unread',
        ]);
    }

    /**
     * 2. Valid submission redirects back with success message.
     */
    public function test_valid_submission_redirects_back_with_success_message(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Sarah Connor',
            'email' => 'sarah@example.com',
            'subject' => 'Planning an Event',
            'message' => 'Need event production for a corporate event.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success', 'Your message has been sent successfully.');
    }

    /**
     * 3. Missing name is rejected.
     */
    public function test_missing_name_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'noname@example.com',
            'subject' => 'Growing a Business',
            'message' => 'Some message content.',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, ContactMessage::count());
    }

    /**
     * 4. Invalid email is rejected.
     */
    public function test_invalid_email_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Test User',
            'email' => 'not-a-valid-email',
            'subject' => 'Growing a Business',
            'message' => 'Some message content.',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, ContactMessage::count());
    }

    /**
     * 5. Missing subject is rejected.
     */
    public function test_missing_subject_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => '',
            'message' => 'Some message content.',
        ]);

        $response->assertSessionHasErrors('subject');
        $this->assertEquals(0, ContactMessage::count());
    }

    /**
     * 6. Missing message is rejected.
     */
    public function test_missing_message_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Planning an Event',
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertEquals(0, ContactMessage::count());
    }

    /**
     * 7. Optional phone number works.
     */
    public function test_optional_phone_number_works(): void
    {
        // Null phone
        $responseNullPhone = $this->post(route('contact.store'), [
            'name' => 'No Phone User',
            'email' => 'nophone@example.com',
            'subject' => 'Something Else',
            'message' => 'Testing submission without phone.',
        ]);

        $responseNullPhone->assertRedirect(route('contact'));
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'nophone@example.com',
            'phone' => null,
        ]);

        // Provided phone
        $responseWithPhone = $this->post(route('contact.store'), [
            'name' => 'With Phone User',
            'email' => 'withphone@example.com',
            'phone' => '612-000-1122',
            'subject' => 'Something Else',
            'message' => 'Testing submission with phone.',
        ]);

        $responseWithPhone->assertRedirect(route('contact'));
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'withphone@example.com',
            'phone' => '612-000-1122',
        ]);
    }

    /**
     * 8. New messages are stored with status "unread".
     */
    public function test_new_messages_are_stored_with_status_unread(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Unread Check User',
            'email' => 'unread@example.com',
            'subject' => 'Building a Personal Brand',
            'message' => 'Testing default unread status.',
        ]);

        $message = ContactMessage::where('email', 'unread@example.com')->first();
        $this->assertNotNull($message);
        $this->assertEquals('unread', $message->status);
    }
}
