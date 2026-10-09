<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::query()->latest();

        $status = $request->query('status');
        if (! empty($status) && in_array($status, ['unread', 'read'], true)) {
            $query->where('status', $status);
        }

        $search = $request->query('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('messages', 'status', 'search'));
    }

    /**
     * Display the specified contact message.
     */
    public function show(ContactMessage $contact): View
    {
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Update the status of the contact message (e.g. read / unread).
     */
    public function updateStatus(Request $request, ContactMessage $contact): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['read', 'unread'])],
        ]);

        $contact->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Message #{$contact->id} marked as {$contact->status}.");
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(ContactMessage $contact): RedirectResponse
    {
        $contactId = $contact->id;
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', "Contact message #{$contactId} has been deleted.");
    }
}
