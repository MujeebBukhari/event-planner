@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'System Overview')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(30, 58, 138, 0.15));
        border: 1px solid rgba(59, 130, 246, 0.25);
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .welcome-text h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.3px;
    }

    .welcome-text p {
        font-size: 14px;
        color: var(--text-secondary);
        margin-top: 4px;
    }

    .welcome-meta {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 9999px;
        background-color: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #34d399;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        transition: transform 0.15s ease, border-color 0.15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59, 130, 246, 0.35);
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .stat-subtext {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-blue {
        background-color: rgba(59, 130, 246, 0.12);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .icon-amber {
        background-color: rgba(245, 158, 11, 0.12);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .icon-emerald {
        background-color: rgba(16, 185, 129, 0.12);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .icon-rose {
        background-color: rgba(244, 63, 94, 0.12);
        color: #fb7185;
        border: 1px solid rgba(244, 63, 94, 0.25);
    }

    .icon-purple {
        background-color: rgba(168, 85, 247, 0.12);
        color: #c084fc;
        border: 1px solid rgba(168, 85, 247, 0.25);
    }

    .icon-cyan {
        background-color: rgba(6, 182, 212, 0.12);
        color: #22d3ee;
        border: 1px solid rgba(6, 182, 212, 0.25);
    }

    .icon-indigo {
        background-color: rgba(99, 102, 241, 0.12);
        color: #818cf8;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    .system-card {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 24px;
    }

    .system-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
    }

    .system-card p {
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .system-list {
        margin-top: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .system-item {
        background-color: var(--bg-surface-elevated);
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .system-item span:first-child {
        color: var(--text-muted);
    }

    .system-item span:last-child {
        color: var(--text-primary);
        font-weight: 600;
    }

    .dashboard-tables {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 32px;
        min-width: 0;
    }

    .dashboard-tables .card {
        margin-bottom: 0;
        min-width: 0;
        width: 100%;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }
    }

    @media (max-width: 640px) {
        .welcome-banner {
            padding: 18px 20px;
        }
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .system-list {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Welcome back, {{ Auth::user()->name }}!</h2>
        <p>RK Vibes Administration Portal &mdash; Step 9A: Authentication initialized successfully.</p>
    </div>
    <div class="welcome-meta">
        <div class="status-badge">
            <span class="status-dot"></span>
            <span>Authentication Active</span>
        </div>
    </div>
</div>

<div class="stats-grid">
    <!-- Total Bookings -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Bookings</span>
            <span class="stat-value">{{ $stats['totalBookings'] }}</span>
            <span class="stat-subtext">All time reservations</span>
        </div>
        <div class="stat-icon icon-blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                <path d="M13 5v2"></path>
                <path d="M13 17v2"></path>
                <path d="M13 11v2"></path>
            </svg>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Pending Bookings</span>
            <span class="stat-value">{{ $stats['pendingBookings'] }}</span>
            <span class="stat-subtext">Awaiting confirmation</span>
        </div>
        <div class="stat-icon icon-amber">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
    </div>

    <!-- Total Contact Messages -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Contact Messages</span>
            <span class="stat-value">{{ $stats['totalMessages'] }}</span>
            <span class="stat-subtext">Public inquiries received</span>
        </div>
        <div class="stat-icon icon-emerald">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>
    </div>

    <!-- Unread Contact Messages -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Unread Contact Messages</span>
            <span class="stat-value">{{ $stats['unreadMessages'] }}</span>
            <span class="stat-subtext">Requires review</span>
        </div>
        <div class="stat-icon icon-rose">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
    </div>

    <!-- Total Services -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Services</span>
            <span class="stat-value">{{ $stats['totalServices'] }}</span>
            <span class="stat-subtext">Configured offerings</span>
        </div>
        <div class="stat-icon icon-purple">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
        </div>
    </div>

    <!-- Total Gallery Items -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Gallery Items</span>
            <span class="stat-value">{{ $stats['totalGalleryItems'] }}</span>
            <span class="stat-subtext">Showcase media assets</span>
        </div>
        <div class="stat-icon icon-cyan">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
        </div>
    </div>

    <!-- Total Testimonials -->
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Testimonials</span>
            <span class="stat-value">{{ $stats['totalTestimonials'] }}</span>
            <span class="stat-subtext">Client reviews</span>
        </div>
        <div class="stat-icon icon-indigo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Featured Event & Ticket Pricing Live Controls -->
<div class="card" style="border: 1px solid rgba(55, 95, 172, 0.35); background: linear-gradient(135deg, rgba(19, 27, 38, 0.95), rgba(15, 23, 42, 0.95)); margin-bottom: 32px;">
    <div class="card-header">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span class="status-badge" style="background: rgba(102, 203, 230, 0.12); border-color: rgba(102, 203, 230, 0.3); color: var(--accent-cyan);">
                    <span class="status-dot" style="background-color: var(--accent-cyan); box-shadow: 0 0 8px rgba(102, 203, 230, 0.7);"></span>
                    Website Hero &amp; Checkout Event
                </span>
            </div>
            <h2 class="card-title" style="font-size: 18px;">Featured Event &amp; Ticket Pricing</h2>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">
                Updating this form immediately syncs the event name, date, and ticket prices on both the <strong>Home Page Hero Card</strong> and the <strong>Tickets Checkout Page</strong>.
            </p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="{{ route('admin.event.edit') }}" class="btn btn-primary btn-sm">
                Full Event Editor &rarr;
            </a>
            <a href="{{ route('tickets') }}" target="_blank" class="btn btn-secondary btn-sm" title="Open public booking page">
                View Public Booking
            </a>
        </div>
    </div>

    <form action="{{ route('admin.event.update') }}" method="POST" style="margin-top: 16px;">
        @csrf
        @method('PUT')
        <input type="hidden" name="tag" value="{{ $event->tag }}">
        <input type="hidden" name="doors_time" value="{{ $event->doors_time }}">
        <input type="hidden" name="general_admission_desc" value="{{ $event->general_admission_desc }}">
        <input type="hidden" name="vip_access_desc" value="{{ $event->vip_access_desc }}">
        <input type="hidden" name="cta_text" value="{{ $event->cta_text }}">
        <input type="hidden" name="note" value="{{ $event->note }}">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="dash_event_title" class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Event Name / Title</label>
                <input type="text" name="title" id="dash_event_title" class="form-input" style="font-size: 14px; font-weight: 600; padding: 10px 14px;" value="{{ $event->title }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="dash_event_meta" class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Location &amp; Date</label>
                <input type="text" name="date_location" id="dash_event_meta" class="form-input" style="font-size: 14px; font-weight: 600; padding: 10px 14px;" value="{{ $event->date_location }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 240px)); gap: 16px; margin-bottom: 16px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="dash_ga_price" class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--accent-cyan); font-weight: 700;">General Admission ($)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 700;">$</span>
                    <input type="number" step="0.01" min="0" name="general_admission_price" id="dash_ga_price" class="form-input" style="padding-left: 26px; font-weight: 700; color: var(--accent-cyan);" value="{{ $event->general_admission_price }}" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="dash_vip_price" class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #a78bfa; font-weight: 700;">VIP Access ($)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 700;">$</span>
                    <input type="number" step="0.01" min="0" name="vip_access_price" id="dash_vip_price" class="form-input" style="padding-left: 26px; font-weight: 700; color: #a78bfa;" value="{{ $event->vip_access_price }}" required>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); padding-top: 14px; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 12px; color: var(--text-muted);">
                Currently live: <strong style="color: var(--text-primary);">{{ $event->title }}</strong> &bull; GA: <span style="color: var(--accent-cyan); font-weight: 700;">{{ $event->ga_price_display }}</span> &bull; VIP: <span style="color: #a78bfa; font-weight: 700;">{{ $event->vip_price_display }}</span>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                Save &amp; Update Live Site
            </button>
        </div>
    </form>
</div>

<!-- Quick Actions Section -->
<div class="quick-actions-section" style="margin-bottom: 32px;">
    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.05em;">Quick Actions</h3>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('admin.event.edit') }}" class="btn btn-primary btn-sm">
            &#9881; Featured Event &amp; Prices
        </a>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
            + Add Service
        </a>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm">
            + Add Gallery Item
        </a>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
            + Add Testimonial
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
            View Bookings
        </a>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
            View Contact Messages
        </a>
    </div>
</div>

<!-- Recent Bookings & Messages Stack -->
<div class="dashboard-tables">
    <!-- Recent Bookings -->
    <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Bookings</h2>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Latest reservation submissions</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                    View All Bookings &rarr;
                </a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Event Type</th>
                            <th>Event Date</th>
                            <th>Guest Count</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    {{ $booking->name }}
                                </td>
                                <td>{{ $booking->event_type ?? 'Standard' }}</td>
                                <td>{{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'TBD' }}</td>
                                <td>{{ $booking->guest_count }}</td>
                                <td style="font-weight: 700; color: var(--text-primary);">
                                    ${{ number_format($booking->total_amount, 2) }}
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
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <h4>No bookings recorded yet</h4>
                                        <p style="font-size: 13px; margin-top: 4px;">Client reservations submitted via the public ticket page will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Contact Messages -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Contact Messages</h2>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Latest inquiries submitted from the website</p>
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
                    View All Messages &rarr;
                </a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sender Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMessages as $msg)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    {{ $msg->name }}
                                </td>
                                <td>
                                    <div style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $msg->subject }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $msg->status }}">
                                        {{ $msg->status }}
                                    </span>
                                </td>
                                <td style="font-size: 12px; color: var(--text-muted);">
                                    {{ $msg->created_at->format('M d, Y') }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.contacts.show', $msg) }}" class="btn btn-secondary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <h4>No contact inquiries yet</h4>
                                        <p style="font-size: 13px; margin-top: 4px;">Messages sent through the contact form will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="system-card">
        <h3>Admin Portal Status</h3>
        <p>Administrative authentication, authorization boundaries, and live data management are fully active.</p>
        
        <div class="system-list">
            <div class="system-item">
                <span>Admin User</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
            <div class="system-item">
                <span>Role</span>
                <span style="text-transform: capitalize; color: #34d399;">{{ Auth::user()->role }}</span>
            </div>
            <div class="system-item">
                <span>Environment</span>
                <span>{{ app()->environment() }}</span>
            </div>
            <div class="system-item">
                <span>Session Status</span>
                <span>Secure &amp; Protected</span>
            </div>
        </div>
    </div>
@endsection
