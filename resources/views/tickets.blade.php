@extends('layouts.app')

@section('title', 'Book Tickets — Fall Gathering 2026 — RK Vibes')

@section('content')
<section class="page-hero" style="padding-bottom:40px;">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Events / Book Tickets</div>
    <span class="eyebrow">Upcoming Event</span>
    <h1>RK Vibes Presents — Fall Gathering 2026</h1>
    <p class="lead">Minneapolis, MN &nbsp;·&nbsp; October 2026 &nbsp;·&nbsp; Doors 6:00 PM</p>
  </div>
</section>

<section style="padding-top:50px;">
  <div class="wrap">
    @if (session('success'))
      <div style="background: rgba(215, 174, 48, 0.12); border: 1px solid var(--gold); color: var(--gold); padding: 16px 22px; margin-bottom: 30px; font-weight: 600; font-size: 14.5px;">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #ff8080; padding: 16px 22px; margin-bottom: 30px; font-size: 14px;">
        <strong style="display:block;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.04em;">Please correct the following:</strong>
        <ul style="padding-left: 20px; margin: 0;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('tickets.store') }}" method="POST">
      @csrf
      <input type="hidden" name="event_type" id="event_type_input" value="{{ old('event_type', 'General Admission') }}">
      <input type="hidden" name="guest_count" id="guest_count_input" value="{{ old('guest_count', 2) }}">
      <input type="hidden" name="payment_method" id="payment_method_input" value="{{ old('payment_method', 'card') }}">

      <div class="checkout-grid">

        <div class="panel">
          <h3>1. Select Tickets</h3>
          <div class="tier-row">
            <div>
              <div class="tier-name">General Admission</div>
              <div class="tier-desc">Standing room, full event access</div>
            </div>
            <div>
              <div class="tier-price">$25</div>
              <div class="qty-control"><button type="button" aria-label="Decrease">&minus;</button><span>{{ old('event_type') === 'General Admission' ? old('guest_count', 2) : (old('event_type') ? 0 : 2) }}</span><button type="button" aria-label="Increase">&plus;</button></div>
            </div>
          </div>
          <div class="tier-row">
            <div>
              <div class="tier-name">VIP Access</div>
              <div class="tier-desc">Front section, early entry, meet &amp; greet</div>
            </div>
            <div>
              <div class="tier-price">$60</div>
              <div class="qty-control"><button type="button" aria-label="Decrease">&minus;</button><span>{{ old('event_type') === 'VIP Access' ? old('guest_count', 0) : 0 }}</span><button type="button" aria-label="Increase">&plus;</button></div>
            </div>
          </div>

          <h3 style="margin-top:32px;">2. Your Details</h3>
          <div class="field-row">
            <div class="field">
              <label>Full Name</label>
              <input type="text" name="name" placeholder="Jordan Smith" value="{{ old('name') }}" required>
            </div>
            <div class="field">
              <label>Email</label>
              <input type="email" name="email" placeholder="jordan@email.com" value="{{ old('email') }}" required>
            </div>
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="(612) 000-0000" value="{{ old('phone') }}">
          </div>

          <h3 style="margin-top:32px;">3. Payment Method</h3>
          <div class="pay-methods">
            <button type="button" class="{{ old('payment_method', 'card') === 'card' ? 'active' : '' }}" data-method="card">Card</button>
            <button type="button" class="{{ old('payment_method') === 'apple_pay' ? 'active' : '' }}" data-method="apple_pay">Apple Pay</button>
            <button type="button" class="{{ old('payment_method') === 'google_pay' ? 'active' : '' }}" data-method="google_pay">Google Pay</button>
          </div>
          <div class="field">
            <label>Card Number</label>
            <input type="text" name="card_number" placeholder="4242 4242 4242 4242" autocomplete="off">
          </div>
          <div class="field-row">
            <div class="field">
              <label>Expiry</label>
              <input type="text" name="card_expiry" placeholder="MM / YY" autocomplete="off">
            </div>
            <div class="field">
              <label>CVC</label>
              <input type="text" name="card_cvc" placeholder="123" autocomplete="off">
            </div>
          </div>
          <div class="field">
            <label>Billing ZIP</label>
            <input type="text" name="billing_zip" placeholder="55401" autocomplete="off">
          </div>
          <div class="card-brands"><span>VISA</span><span>MASTERCARD</span><span>AMEX</span><span>DISCOVER</span></div>
        </div>

        <div class="panel" style="position:sticky;top:100px;">
          <h3>Order Summary</h3>
          <div class="summary-line"><span>General Admission &times; 2</span><span>$50.00</span></div>
          <div class="summary-line"><span>VIP Access &times; 0</span><span>$0.00</span></div>
          <div class="summary-line"><span>Processing Fee</span><span>$0.00</span></div>
          <div class="summary-line total"><span>Total</span><b>$50.00</b></div>
          <button type="submit" class="btn btn-solid" style="width:100%;justify-content:center;margin-top:22px;">Pay $50.00 Securely</button>
          <div class="lock-note">&#128274; Processed directly via Stripe — your card details never touch our servers</div>
        </div>

      </div>
    </form>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap">
    <p style="text-align:center;color:var(--muted);font-size:12.5px;max-width:520px;margin:0 auto;">
      This is a layout demo — payment fields are illustrative only. The real build wires this form to Stripe Checkout / Payment Element for live card processing.
    </p>
  </div>
</section>
@endsection
