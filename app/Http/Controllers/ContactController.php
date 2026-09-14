<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message and dispatch notification.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        $adminEmail = config('mail.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
            } catch (Throwable $e) {
                Log::error('Failed to send contact notification email: '.$e->getMessage(), [
                    'contact_message_id' => $contactMessage->id,
                ]);
            }
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Your message has been sent successfully.');
    }
}
