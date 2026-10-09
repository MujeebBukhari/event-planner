@extends('admin.layouts.app')

@section('title', 'Booking #' . $booking->id . ' Details')
@section('header_title', 'Booking #' . $booking->id)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to All Bookings
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    <!-- Main Details Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <span class="badge badge-{{ $booking->status }}" style="margin-bottom: 8px;">
                    {{ $booking->status }}
                </span>
                <h2 class="card-title">Booking Information &mdash; #{{ $booking->id }}</h2>
            </div>
            <div style="font-size: 13px; color: var(--text-muted);">
                Placed on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 24px; margin-bottom: 24px;">
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Customer Name</div>
                <div style="font-size: 15px; font-weight: 700; color: var(--text-primary);">{{ $booking->name }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Email Address</div>
                <div style="font-size: 14px; color: var(--text-primary);">
                    <a href="mailto:{{ $booking->email }}" style="color: var(--accent-primary); text-decoration: none;">{{ $booking->email }}</a>
                </div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Phone Number</div>
                <div style="font-size: 14px; color: var(--text-primary);">{{ $booking->phone ?? 'Not provided' }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Event Type</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $booking->event_type ?? 'General' }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Event Date</div>
                <div style="font-size: 14px; color: var(--text-primary);">
                    {{ $booking->event_date ? $booking->event_date->format('F d, Y') : 'Date to be confirmed' }}
                </div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Guest Count</div>
                <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $booking->guest_count }} ticket(s)</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Total Amount</div>
                <div style="font-size: 18px; font-weight: 800; color: var(--accent-emerald);">
                    ${{ number_format($booking->total_amount, 2) }}
                </div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Payment Method</div>
                <div style="font-size: 14px; text-transform: uppercase; font-weight: 600; color: var(--text-secondary);">
                    {{ $booking->payment_method ?? 'Stripe' }}
                </div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Payment Status</div>
                <div>
                    @if($booking->payment_status === 'paid')
                        <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 12px; padding: 3px 9px;">Paid</span>
                    @elseif($booking->payment_status === 'failed')
                        <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 12px; padding: 3px 9px;">Failed</span>
                    @elseif($booking->payment_status === 'refunded')
                        <span class="badge" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); font-size: 12px; padding: 3px 9px;">Refunded</span>
                    @else
                        <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 12px; padding: 3px 9px;">Unpaid</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stripe Integration Details -->
        <div style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 18px 20px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--accent-primary); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    Stripe Checkout Details
                </h4>
                <span style="font-size: 11px; padding: 2px 8px; border-radius: 12px; background: rgba(59, 130, 246, 0.12); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); font-weight: 600;">Test Mode</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px; font-size: 13px;">
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 3px; font-size: 11px; text-transform: uppercase;">Checkout Session ID</span>
                    <span style="font-family: monospace; font-size: 12px; color: var(--text-primary); word-break: break-all;">
                        {{ $booking->stripe_session_id ?? 'Not generated / pending' }}
                    </span>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 3px; font-size: 11px; text-transform: uppercase;">Payment Intent ID</span>
                    <span style="font-family: monospace; font-size: 12px; color: var(--text-primary); word-break: break-all;">
                        {{ $booking->stripe_payment_intent_id ?? 'None' }}
                    </span>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 3px; font-size: 11px; text-transform: uppercase;">Paid At</span>
                    <span style="color: var(--text-primary);">
                        {{ $booking->paid_at ? $booking->paid_at->format('M d, Y \a\t h:i:s A') : 'Pending verification' }}
                    </span>
                </div>
            </div>

            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 14px; padding-top: 10px; border-top: 1px dashed var(--border-subtle); display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Zero Card Storage Policy: Sensitive card details are handled exclusively on Stripe's PCI-DSS compliant checkout.</span>
            </div>
        </div>

        @if($booking->message)
            <div style="margin-top: 20px;">
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Customer Notes / Requests</div>
                <div style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 16px; font-size: 14px; color: var(--text-primary); line-height: 1.6;">
                    {{ $booking->message }}
                </div>
            </div>
        @endif
    </div>

    <!-- Actions & Status Sidebar -->
    <div>
        <div class="card">
            <h3 class="card-title" style="margin-bottom: 16px;">Manage Booking</h3>

            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" style="margin-bottom: 24px;">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="status" class="form-label">Update Status</label>
                    <select name="status" id="status" class="form-select" style="width: 100%;">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Save Status
                </button>
            </form>

            <div style="border-top: 1px solid var(--border-subtle); padding-top: 20px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Danger Zone</div>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Permanently delete Booking #{{ $booking->id }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
