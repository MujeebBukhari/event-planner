@extends('admin.layouts.app')

@section('title', 'Featured Event & Ticket Pricing')
@section('header_title', 'Featured Event & Ticket Pricing')

@section('content')
<style>
    .event-management-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(340px, 420px);
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 1120px) {
        .event-management-grid {
            grid-template-columns: 1fr;
        }
    }

    .event-input-large {
        width: 100% !important;
        box-sizing: border-box !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        padding: 12px 14px !important;
        border-radius: 8px !important;
    }

    .event-date-row {
        display: grid;
        grid-template-columns: 2.1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 680px) {
        .event-date-row {
            grid-template-columns: 1fr;
        }
    }

    /* Live Preview Hero Card (mirrors public site) */
    .preview-container {
        position: sticky;
        top: 96px;
    }

    .preview-header-note {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .preview-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--accent-cyan);
    }

    .preview-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--accent-cyan);
        box-shadow: 0 0 8px rgba(102, 203, 230, 0.7);
        animation: pulse-dot 2s infinite ease-in-out;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    .public-preview-card {
        background: #151e2b;
        border: 1px solid #223043;
        border-radius: 8px;
        padding: 28px;
        position: relative;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .public-preview-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(120deg, #029d58 0%, #375fac 50%, #66cbe6 100%);
        border-radius: 8px 8px 0 0;
    }

    .public-preview-card .card-tag {
        display: inline-block;
        background: linear-gradient(135deg, #017b45 0%, #375fac 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 5px 12px;
        text-transform: uppercase;
        margin-bottom: 14px;
        border-radius: 4px;
    }

    .public-preview-card h3 {
        font-size: 21px;
        font-weight: 800;
        line-height: 1.25;
        color: #f5f8fc;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: -0.01em;
    }

    .public-preview-card .card-meta {
        color: #6e819b;
        font-size: 13.5px;
        margin-bottom: 20px;
    }

    .public-preview-card .card-meta strong {
        color: #9fb0c7;
    }

    .public-preview-card .ticket-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-top: 1px dashed #223043;
    }

    .public-preview-card .ticket-row:last-of-type {
        border-bottom: 1px dashed #223043;
        margin-bottom: 20px;
    }

    .public-preview-card .ticket-info .name {
        font-size: 14px;
        font-weight: 600;
        color: #f5f8fc;
        display: block;
    }

    .public-preview-card .ticket-info .desc {
        font-size: 11.5px;
        color: #6e819b;
        margin-top: 2px;
        display: block;
    }

    .public-preview-card .ticket-price {
        font-size: 18px;
        font-weight: 800;
        color: #66cbe6;
        text-shadow: 0 0 10px rgba(102, 203, 230, 0.25);
    }

    .public-preview-card .btn-preview-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 13px 20px;
        background: linear-gradient(135deg, #017b45 0%, #375fac 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 4px;
        border: none;
        box-shadow: 0 4px 14px rgba(1, 123, 69, 0.35);
        cursor: default;
    }

    .public-preview-card .card-secure-note {
        text-align: center;
        font-size: 11px;
        color: #6e819b;
        margin-top: 10px;
    }

    .section-helper-text {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .price-input-wrapper {
        position: relative;
    }

    .price-currency-sign {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-weight: 700;
        font-size: 15px;
        pointer-events: none;
    }

    .price-input {
        padding-left: 28px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        color: var(--accent-cyan) !important;
    }
</style>

<div class="card" style="margin-bottom: 28px;">
    <div class="card-header" style="margin-bottom: 0;">
        <div>
            <h2 class="card-title">Live Website Event &amp; Ticketing</h2>
            <p class="section-helper-text" style="margin-bottom: 0; margin-top: 4px;">
                Changes saved here instantly update both the <strong>Home Page Hero Card</strong> and the <strong>Checkout Booking Page</strong>.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                View Home Hero
            </a>
            <a href="{{ route('tickets') }}" target="_blank" class="btn btn-secondary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                View Booking Page
            </a>
        </div>
    </div>
</div>

<div class="event-management-grid">
    <!-- Form Column -->
    <div>
        <form action="{{ route('admin.event.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 1. Event Identification -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">1. Event Information</h3>
                </div>

                <div class="form-group">
                    <label for="tag" class="form-label">Badge / Tag Text</label>
                    <input 
                        type="text" 
                        name="tag" 
                        id="tag" 
                        class="form-input @error('tag') is-invalid @enderror" 
                        value="{{ old('tag', $event->tag) }}" 
                        placeholder="Upcoming Event" 
                        required
                    >
                    @error('tag') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="title" class="form-label" style="font-size: 13.5px; font-weight: 700;">Event Title / Name</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="form-input event-input-large @error('title') is-invalid @enderror" 
                        value="{{ old('title', $event->title) }}" 
                        placeholder="RK Vibes Presents — Fall Gathering 2026" 
                        required
                    >
                    <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 5px; display: block;">
                        Tip: Use an em-dash (—) or hyphen to break subtitle cleanly on the home page card.
                    </span>
                    @error('title') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="event-date-row">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="date_location" class="form-label" style="font-size: 13px; font-weight: 600;">Location &amp; Date</label>
                        <input 
                            type="text" 
                            name="date_location" 
                            id="date_location" 
                            class="form-input event-input-large @error('date_location') is-invalid @enderror" 
                            value="{{ old('date_location', $event->date_location) }}" 
                            placeholder="Minneapolis, MN · Oct 2026" 
                            required
                        >
                        @error('date_location') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="doors_time" class="form-label" style="font-size: 13px; font-weight: 600;">Doors / Time</label>
                        <input 
                            type="text" 
                            name="doors_time" 
                            id="doors_time" 
                            class="form-input event-input-large @error('doors_time') is-invalid @enderror" 
                            value="{{ old('doors_time', $event->doors_time) }}" 
                            placeholder="Doors 6:00 PM"
                        >
                        @error('doors_time') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Ticket Pricing -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">2. Ticket Tiers &amp; Pricing</h3>
                </div>

                <!-- General Admission Tier -->
                <div style="background: rgba(11, 15, 23, 0.4); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 18px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <strong style="color: var(--text-primary); font-size: 14px;">Tier 1: General Admission</strong>
                        <span style="font-size: 11.5px; color: var(--accent-cyan); font-weight: 600;">Default Selection</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="general_admission_price" class="form-label">Price (USD)</label>
                            <div class="price-input-wrapper">
                                <span class="price-currency-sign">$</span>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    name="general_admission_price" 
                                    id="general_admission_price" 
                                    class="form-input price-input @error('general_admission_price') is-invalid @enderror" 
                                    value="{{ old('general_admission_price', $event->general_admission_price) }}" 
                                    placeholder="25.00" 
                                    required
                                >
                            </div>
                            @error('general_admission_price') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="general_admission_desc" class="form-label">Tier Description</label>
                            <input 
                                type="text" 
                                name="general_admission_desc" 
                                id="general_admission_desc" 
                                class="form-input @error('general_admission_desc') is-invalid @enderror" 
                                value="{{ old('general_admission_desc', $event->general_admission_desc) }}" 
                                placeholder="Standing room, full event access"
                            >
                            @error('general_admission_desc') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- VIP Access Tier -->
                <div style="background: rgba(11, 15, 23, 0.4); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 18px; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <strong style="color: var(--text-primary); font-size: 14px;">Tier 2: VIP Access</strong>
                        <span style="font-size: 11.5px; color: #a78bfa; font-weight: 600;">Premium Tier</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="vip_access_price" class="form-label">Price (USD)</label>
                            <div class="price-input-wrapper">
                                <span class="price-currency-sign">$</span>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    name="vip_access_price" 
                                    id="vip_access_price" 
                                    class="form-input price-input @error('vip_access_price') is-invalid @enderror" 
                                    value="{{ old('vip_access_price', $event->vip_access_price) }}" 
                                    placeholder="60.00" 
                                    required
                                >
                            </div>
                            @error('vip_access_price') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="vip_access_desc" class="form-label">Tier Description</label>
                            <input 
                                type="text" 
                                name="vip_access_desc" 
                                id="vip_access_desc" 
                                class="form-input @error('vip_access_desc') is-invalid @enderror" 
                                value="{{ old('vip_access_desc', $event->vip_access_desc) }}" 
                                placeholder="Front section, early entry, meet & greet"
                            >
                            @error('vip_access_desc') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Button & Checkout Copy -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">3. Checkout Button &amp; Footnote</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cta_text" class="form-label">Button CTA Label</label>
                        <input 
                            type="text" 
                            name="cta_text" 
                            id="cta_text" 
                            class="form-input @error('cta_text') is-invalid @enderror" 
                            value="{{ old('cta_text', $event->cta_text) }}" 
                            placeholder="Book Now — Secure Checkout"
                        >
                        @error('cta_text') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="note" class="form-label">Trust Footnote Note</label>
                        <input 
                            type="text" 
                            name="note" 
                            id="note" 
                            class="form-input @error('note') is-invalid @enderror" 
                            value="{{ old('note', $event->note) }}" 
                            placeholder="Paid direct via Stripe — no third-party ticketing fees"
                        >
                        @error('note') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 14px; align-items: center; margin-top: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 13px 28px; font-size: 14px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Save Changes to Live Website
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Live Preview Column -->
    <div class="preview-container">
        <div class="preview-header-note">
            <span class="preview-badge">
                <span class="preview-live-dot"></span>
                Live Website Card Preview
            </span>
            <span style="font-size: 11px; color: var(--text-muted);">Home Page Hero</span>
        </div>

        <div class="public-preview-card">
            <span class="card-tag" id="preview_tag">{{ $event->tag }}</span>
            <h3 id="preview_title">{{ $event->title }}</h3>
            <p class="card-meta">
                <span id="preview_meta">{{ $event->date_location }}</span>
            </p>

            <div class="ticket-row">
                <div class="ticket-info">
                    <span class="name">General Admission</span>
                    <span class="desc" id="preview_ga_desc">{{ $event->general_admission_desc }}</span>
                </div>
                <div class="ticket-price" id="preview_ga_price">{{ $event->ga_price_display }}</div>
            </div>

            <div class="ticket-row">
                <div class="ticket-info">
                    <span class="name">VIP Access</span>
                    <span class="desc" id="preview_vip_desc">{{ $event->vip_access_desc }}</span>
                </div>
                <div class="ticket-price" id="preview_vip_price">{{ $event->vip_price_display }}</div>
            </div>

            <div class="btn-preview-cta" id="preview_cta">
                {{ $event->cta_text }}
            </div>

            <div class="card-secure-note" id="preview_note">
                {{ $event->note }}
            </div>
        </div>

        <div style="margin-top: 18px; padding: 16px; background: rgba(55, 95, 172, 0.1); border: 1px dashed rgba(102, 203, 230, 0.35); border-radius: 8px;">
            <div style="display: flex; gap: 8px; align-items: center; color: var(--accent-cyan); font-weight: 700; font-size: 13px; margin-bottom: 6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Checkout Sync Guarantee
            </div>
            <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                When you change the General Admission or VIP price above, Stripe Checkout and customer order totals immediately recalculate to reflect the new prices automatically.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function formatPrice(val) {
            var num = parseFloat(val);
            if (isNaN(num)) return '$0';
            return num % 1 === 0 ? '$' + num : '$' + num.toFixed(2);
        }

        var inputs = {
            tag: document.getElementById('tag'),
            title: document.getElementById('title'),
            date_location: document.getElementById('date_location'),
            general_admission_price: document.getElementById('general_admission_price'),
            vip_access_price: document.getElementById('vip_access_price'),
            general_admission_desc: document.getElementById('general_admission_desc'),
            vip_access_desc: document.getElementById('vip_access_desc'),
            cta_text: document.getElementById('cta_text'),
            note: document.getElementById('note')
        };

        var previews = {
            tag: document.getElementById('preview_tag'),
            title: document.getElementById('preview_title'),
            meta: document.getElementById('preview_meta'),
            ga_price: document.getElementById('preview_ga_price'),
            vip_price: document.getElementById('preview_vip_price'),
            ga_desc: document.getElementById('preview_ga_desc'),
            vip_desc: document.getElementById('preview_vip_desc'),
            cta: document.getElementById('preview_cta'),
            note: document.getElementById('preview_note')
        };

        function syncPreview() {
            if (inputs.tag && previews.tag) previews.tag.textContent = inputs.tag.value || 'Upcoming Event';
            if (inputs.title && previews.title) previews.title.textContent = inputs.title.value || 'RK Vibes Event';
            if (inputs.date_location && previews.meta) previews.meta.textContent = inputs.date_location.value || 'Minneapolis, MN';
            if (inputs.general_admission_price && previews.ga_price) previews.ga_price.textContent = formatPrice(inputs.general_admission_price.value);
            if (inputs.vip_access_price && previews.vip_price) previews.vip_price.textContent = formatPrice(inputs.vip_access_price.value);
            if (inputs.general_admission_desc && previews.ga_desc) previews.ga_desc.textContent = inputs.general_admission_desc.value;
            if (inputs.vip_access_desc && previews.vip_desc) previews.vip_desc.textContent = inputs.vip_access_desc.value;
            if (inputs.cta_text && previews.cta) previews.cta.textContent = inputs.cta_text.value || 'Book Now — Secure Checkout';
            if (inputs.note && previews.note) previews.note.textContent = inputs.note.value;
        }

        Object.values(inputs).forEach(function (input) {
            if (input) {
                input.addEventListener('input', syncPreview);
            }
        });
    });
</script>
@endpush
@endsection
