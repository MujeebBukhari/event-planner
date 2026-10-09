@extends('admin.layouts.app')

@section('title', 'Event Bookings')
@section('header_title', 'Event Bookings')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">All Reservations &amp; Bookings</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Review and manage client ticket bookings and reservations.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-bar" style="flex-wrap: wrap; gap: 14px;">
        <div class="filter-group">
            <label for="status" style="font-size: 13px; color: var(--text-muted);">Status:</label>
            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="payment_status" style="font-size: 13px; color: var(--text-muted);">Payment:</label>
            <select name="payment_status" id="payment_status" class="form-select" onchange="this.form.submit()">
                <option value="">All Payments</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="date_from" style="font-size: 13px; color: var(--text-muted);">From Date:</label>
            <input 
                type="date" 
                id="date_from"
                name="date_from" 
                value="{{ request('date_from') }}" 
                class="form-input"
                style="width: 140px; padding: 6px 10px; font-size: 13px;"
            >
        </div>

        <div class="filter-group">
            <label for="date_to" style="font-size: 13px; color: var(--text-muted);">To Date:</label>
            <input 
                type="date" 
                id="date_to"
                name="date_to" 
                value="{{ request('date_to') }}" 
                class="form-input"
                style="width: 140px; padding: 6px 10px; font-size: 13px;"
            >
        </div>

        <div class="filter-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search name, email, phone..." 
                class="form-input"
                style="width: 220px;"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request('status') || request('payment_status') || request('search') || request('date_from') || request('date_to'))
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>
    </form>

    <!-- Bookings Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Email &amp; Phone</th>
                    <th>Event Type</th>
                    <th>Event Date</th>
                    <th>Guests</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Booked At</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-muted);">#{{ $booking->id }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $booking->name }}</div>
                        </td>
                        <td>
                            <div>{{ $booking->email }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $booking->phone ?? '—' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 500;">{{ $booking->event_type ?? 'General' }}</span>
                        </td>
                        <td>
                            {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'TBD' }}
                        </td>
                        <td>{{ $booking->guest_count }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">
                            ${{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td>
                            <div style="text-transform: uppercase; font-size: 11px; font-weight: 600; color: var(--text-secondary);">
                                {{ $booking->payment_method ?? 'stripe' }}
                            </div>
                            <div style="margin-top: 4px;">
                                @if($booking->payment_status === 'paid')
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 10.5px; padding: 2px 7px;">Paid</span>
                                @elseif($booking->payment_status === 'failed')
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 10.5px; padding: 2px 7px;">Failed</span>
                                @elseif($booking->payment_status === 'refunded')
                                    <span class="badge" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-size: 10.5px; padding: 2px 7px;">Refunded</span>
                                @else
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 10.5px; padding: 2px 7px;">Unpaid</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $booking->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div class="table-actions" style="justify-content: flex-end;">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Are you sure you want to delete Booking #{{ $booking->id }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                                </svg>
                                <h4>No bookings found</h4>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if(request('search') || request('status') || request('date_from') || request('date_to'))
                                        No bookings match the selected search or filter criteria. <a href="{{ route('admin.bookings.index') }}" style="color: var(--accent-primary);">Clear filters</a> to view all.
                                    @else
                                        No customer reservations have been submitted yet.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="pagination-container">
            <div>Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results</div>
            <div>{{ $bookings->links() }}</div>
        </div>
    @endif
</div>
@endsection
