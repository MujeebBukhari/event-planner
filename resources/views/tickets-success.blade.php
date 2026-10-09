@extends('layouts.app')

@section('title', 'Booking Confirmation — RK Vibes')

@section('content')
<section class="page-hero" style="text-align: center; padding: 80px 0 100px;">
  <div class="wrap" style="max-width: 760px; margin: 0 auto;">
    <div style="width: 72px; height: 72px; margin: 0 auto 24px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 2px solid #10b981; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #10b981;">
      &#10003;
    </div>
    
    <span class="eyebrow" style="color: var(--gold); letter-spacing: 0.15em;">Reservation Confirmation</span>
    <h1 style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin: 16px 0;">Thank You, {{ $booking->name }}!</h1>
    
    @if($booking->isPaid())
      <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: 8px; padding: 14px 20px; margin: 20px auto 30px; max-width: 520px; color: #34d399; font-weight: 600; font-size: 14.5px;">
        &#128274; Payment Verified &amp; Confirmed via Stripe
      </div>
    @else
      <div style="background: rgba(55, 95, 172, 0.14); border: 1px solid var(--rk-blue); border-radius: 8px; padding: 14px 20px; margin: 20px auto 30px; max-width: 520px; color: var(--rk-cyan); font-size: 14px; line-height: 1.5;">
        &#9203; Your reservation has been recorded. Once your payment clears through Stripe webhook processing, your booking confirmation will be fully finalized.
      </div>
    @endif

    <div style="background: #181818; border: 1px solid #2a2a2a; border-radius: 12px; padding: 28px 32px; text-align: left; margin: 30px auto; max-width: 560px;">
      <h3 style="font-size: 16px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--gold); margin-bottom: 20px; border-bottom: 1px solid #2a2a2a; padding-bottom: 12px;">Booking Summary</h3>
      
      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Booking Reference</span>
        <strong style="color: #fff;">#{{ $booking->id }}</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Event Tier</span>
        <strong style="color: #fff;">{{ $booking->event_type }}</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Tickets / Guests</span>
        <strong style="color: #fff;">{{ $booking->guest_count }} ticket(s)</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Total Amount</span>
        <strong style="color: var(--gold); font-size: 16px;">${{ number_format($booking->total_amount, 2) }}</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Payment Status</span>
        @if($booking->isPaid())
          <span style="color: #34d399; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em;">Paid</span>
        @else
          <span style="color: var(--gold); font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em;">Pending Verification</span>
        @endif
      </div>

      @if($booking->stripe_payment_intent_id)
      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Stripe Transaction ID</span>
        <code style="color: var(--gold); font-family: monospace; font-size: 12px;">{{ $booking->stripe_payment_intent_id }}</code>
      </div>
      @endif

      @if($booking->paid_at)
      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Paid On</span>
        <span style="color: #fff;">{{ $booking->paid_at->format('M d, Y · h:i A') }}</span>
      </div>
      @endif

      <div style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px;">
        <span style="color: var(--muted);">Confirmation Sent To</span>
        <strong style="color: #fff;">{{ $booking->email }}</strong>
      </div>
    </div>

    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-top: 30px;">
      <a href="{{ route('home') }}" class="btn btn-solid" style="padding: 12px 28px;">Return to Homepage</a>
      <button type="button" onclick="window.print()" class="btn btn-outline" style="padding: 12px 28px; cursor: pointer;">Print Receipt</button>
      <a href="{{ route('solutions') }}" class="btn btn-outline" style="padding: 12px 28px;">Explore Our Work</a>
    </div>
  </div>
</section>
@endsection
