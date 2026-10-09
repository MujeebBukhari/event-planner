@extends('layouts.app')

@section('title', 'Checkout Cancelled — RK Vibes')

@section('content')
<section class="page-hero" style="text-align: center; padding: 80px 0 100px;">
  <div class="wrap" style="max-width: 760px; margin: 0 auto;">
    <div style="width: 72px; height: 72px; margin: 0 auto 24px; border-radius: 50%; background: rgba(239, 68, 68, 0.15); border: 2px solid #ef4444; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #ef4444;">
      &#10005;
    </div>
    
    <span class="eyebrow" style="color: #ef4444; letter-spacing: 0.15em;">Checkout Incomplete</span>
    <h1 style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin: 16px 0;">Payment Was Not Completed</h1>
    
    @if(session('error'))
      <div style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #ff8080; padding: 14px 20px; margin: 15px auto 25px; max-width: 540px; font-size: 14px; border-radius: 8px;">
        {{ session('error') }}
      </div>
    @endif

    <div style="background: rgba(239, 68, 68, 0.10); border: 1px solid rgba(239, 68, 68, 0.30); border-radius: 8px; padding: 16px 20px; margin: 20px auto 30px; max-width: 540px; color: #fca5a5; font-size: 14.5px; line-height: 1.6;">
      Your checkout session was cancelled or timed out. No funds have been deducted from your card. You can safely try again or select different tickets whenever you are ready.
    </div>

    @if($booking)
    <div style="background: #181818; border: 1px solid #2a2a2a; border-radius: 12px; padding: 24px 32px; text-align: left; margin: 30px auto; max-width: 560px;">
      <h3 style="font-size: 15px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--gold); margin-bottom: 16px; border-bottom: 1px solid #2a2a2a; padding-bottom: 10px;">Reserved Order</h3>
      
      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Reference</span>
        <strong style="color: #fff;">#{{ $booking->id }}</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #222; font-size: 14px;">
        <span style="color: var(--muted);">Tier</span>
        <strong style="color: #fff;">{{ $booking->event_type }} ({{ $booking->guest_count }} guests)</strong>
      </div>

      <div style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px;">
        <span style="color: var(--muted);">Status</span>
        <span style="color: #ef4444; font-weight: 700; text-transform: uppercase; font-size: 12px;">Unpaid</span>
      </div>
    </div>
    @endif

    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-top: 30px;">
      <a href="{{ route('tickets') }}" class="btn btn-solid" style="padding: 12px 28px;">Return to Tickets &amp; Try Again</a>
      <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 12px 28px;">Return to Homepage</a>
    </div>
  </div>
</section>
@endsection
