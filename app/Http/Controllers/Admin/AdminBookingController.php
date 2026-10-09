<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\EventBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    /**
     * Display a paginated listing of event bookings with optional filtering.
     */
    public function index(Request $request): View
    {
        $query = EventBooking::query()->latest();

        $status = $request->query('status');
        if (! empty($status) && in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'], true)) {
            $query->where('status', $status);
        }

        $paymentStatus = $request->query('payment_status');
        if (! empty($paymentStatus) && in_array($paymentStatus, ['unpaid', 'paid', 'failed', 'refunded'], true)) {
            $query->where('payment_status', $paymentStatus);
        }

        $search = $request->query('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%")
                    ->orWhere('stripe_session_id', 'like', "%{$search}%")
                    ->orWhere('stripe_payment_intent_id', 'like', "%{$search}%");
            });
        }

        $dateFrom = $request->query('date_from');
        if (! empty($dateFrom)) {
            $query->whereDate('event_date', '>=', $dateFrom);
        }

        $dateTo = $request->query('date_to');
        if (! empty($dateTo)) {
            $query->whereDate('event_date', '<=', $dateTo);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'status', 'paymentStatus', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Display the specified booking details.
     */
    public function show(EventBooking $booking): View
    {
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update the booking status.
     */
    public function update(UpdateBookingStatusRequest $request, EventBooking $booking): RedirectResponse
    {
        $booking->update([
            'status' => $request->validated('status'),
        ]);

        return back()->with('success', "Booking #{$booking->id} status updated to ".ucfirst($booking->status).'.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(EventBooking $booking): RedirectResponse
    {
        $bookingId = $booking->id;
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', "Booking #{$bookingId} has been deleted.");
    }
}
