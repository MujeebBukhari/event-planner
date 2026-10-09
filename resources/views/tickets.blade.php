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
      <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid #10b981; color: #34d399; padding: 16px 22px; margin-bottom: 25px; font-weight: 600; font-size: 14.5px; border-radius: 6px;">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #ff8080; padding: 16px 22px; margin-bottom: 25px; font-size: 14.5px; border-radius: 6px; display: flex; align-items: flex-start; gap: 12px;">
        <span style="font-size: 18px; line-height: 1;">&#9888;</span>
        <div>
          <strong style="display:block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.04em; color: #ff9999;">Payment Error</strong>
          <span>{{ session('error') }}</span>
        </div>
      </div>
    @endif

    @if (session('warning'))
      <div style="background: rgba(55, 95, 172, 0.14); border: 1px solid var(--rk-blue); color: var(--rk-cyan); padding: 16px 22px; margin-bottom: 25px; font-size: 14.5px; border-radius: 6px; display: flex; align-items: flex-start; gap: 12px;">
        <span style="font-size: 18px; line-height: 1;">&#9432;</span>
        <div>{{ session('warning') }}</div>
      </div>
    @endif

    @if (! config('services.stripe.secret') || config('services.stripe.secret') === 'sk_test_placeholder')
      <div style="background: rgba(55, 95, 172, 0.10); border: 1px dashed rgba(102, 203, 230, 0.5); color: #9edff0; padding: 14px 20px; margin-bottom: 25px; font-size: 13.5px; border-radius: 6px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 16px; color: var(--rk-cyan);">&#9881;</span>
          <span><strong>Stripe Setup Notice:</strong> Add your Stripe API keys (<code>STRIPE_KEY</code> &amp; <code>STRIPE_SECRET</code>) in <code>.env</code> to connect directly to Stripe Checkout.</span>
        </div>
        <span style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: var(--grad-brand); padding: 4px 10px; border-radius: 4px; color: #fff;">Stripe Test Mode</span>
      </div>
    @endif

    @if ($errors->any())
      <div style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #ff8080; padding: 16px 22px; margin-bottom: 25px; font-size: 14px; border-radius: 6px;">
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

          <h3 style="margin-top:32px;">3. Secure Payment</h3>
          <div style="background: rgba(55, 95, 172, 0.10); border: 1px solid rgba(102, 203, 230, 0.35); border-radius: 8px; padding: 20px; margin-top: 14px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <span style="font-size: 20px;">&#128274;</span>
              <strong style="color: var(--rk-cyan); font-size: 15px;">Hosted by Stripe Checkout</strong>
            </div>
            <p style="color: rgba(255, 255, 255, 0.85); font-size: 13.5px; line-height: 1.6; margin: 0 0 14px 0;">
              When you submit your booking, you will be redirected to Stripe's secure, PCI-compliant checkout page to complete your payment.
            </p>
            <div style="display: flex; gap: 14px; flex-wrap: wrap; font-size: 12px; color: var(--rk-cyan);">
              <span>&#10003; Credit &amp; Debit Cards</span>
              <span>&#10003; Apple Pay</span>
              <span>&#10003; Google Pay</span>
              <span>&#10003; 256-bit SSL Encryption</span>
            </div>
          </div>
          <div class="card-brands" style="margin-top: 16px;"><span>VISA</span><span>MASTERCARD</span><span>AMEX</span><span>DISCOVER</span><span>APPLE PAY</span><span>GOOGLE PAY</span></div>
        </div>

        <div class="panel" style="position:sticky;top:100px;">
          <h3>Order Summary</h3>
          <div class="summary-line"><span>General Admission &times; 2</span><span>$50.00</span></div>
          <div class="summary-line"><span>VIP Access &times; 0</span><span>$0.00</span></div>
          <div class="summary-line"><span>Processing Fee</span><span>$0.00</span></div>
          <div class="summary-line total"><span>Total</span><b>$50.00</b></div>
          <button type="submit" class="btn btn-solid" style="width:100%;justify-content:center;margin-top:22px;">Proceed to Stripe Checkout — $50.00</button>
          <div class="lock-note">&#128274; Processed directly via Stripe — your card details never touch our servers</div>
        </div>

      </div>
    </form>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap">
    <p style="text-align:center;color:var(--muted);font-size:12.5px;max-width:540px;margin:0 auto;">
      All ticket transactions are processed securely via Stripe. Sensitive payment details never touch or get stored on our servers.
    </p>
  </div>
</section>
@endsection
