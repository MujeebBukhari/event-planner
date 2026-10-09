<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    /**
     * Display the form for editing the featured event and ticket prices.
     */
    public function edit(): View
    {
        $event = EventSetting::current();

        return view('admin.event.edit', compact('event'));
    }

    /**
     * Update the featured event details and ticket pricing.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tag' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'date_location' => ['required', 'string', 'max:255'],
            'doors_time' => ['nullable', 'string', 'max:100'],
            'general_admission_price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'vip_access_price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'general_admission_desc' => ['nullable', 'string', 'max:255'],
            'vip_access_desc' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $event = EventSetting::current();
        $event->update([
            'tag' => $validated['tag'],
            'title' => $validated['title'],
            'date_location' => $validated['date_location'],
            'doors_time' => $validated['doors_time'] ?? 'Doors 6:00 PM',
            'general_admission_price' => (float) $validated['general_admission_price'],
            'vip_access_price' => (float) $validated['vip_access_price'],
            'general_admission_desc' => $validated['general_admission_desc'] ?? 'Standing room, full event access',
            'vip_access_desc' => $validated['vip_access_desc'] ?? 'Front section, early entry, meet & greet',
            'cta_text' => $validated['cta_text'] ?: 'Book Now — Secure Checkout',
            'note' => $validated['note'] ?: 'Paid direct via Stripe — no third-party ticketing fees',
        ]);

        return back()->with('success', 'Featured event details and ticket pricing updated successfully!');
    }
}
