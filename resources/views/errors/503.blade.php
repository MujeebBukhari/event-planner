@extends('layouts.app')

@section('title', '503 — Under Scheduled Maintenance | RK Vibes')

@section('content')
<section class="page-hero" style="text-align: center; padding: 100px 0 120px;">
  <div class="wrap" style="max-width: 700px; margin: 0 auto;">
    <span class="eyebrow" style="color: var(--gold); letter-spacing: 0.15em;">Maintenance</span>
    <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin: 16px 0;">We'll Be Right Back</h1>
    <p class="lead" style="color: rgba(255, 255, 255, 0.7); font-size: 1.15rem; margin-bottom: 35px;">
      RK Vibes is currently undergoing brief scheduled maintenance. We appreciate your patience and will return shortly.
    </p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="javascript:location.reload()" class="btn btn-solid" style="padding: 12px 28px;">Check Again</a>
      <a href="{{ route('contact') }}" class="btn btn-outline" style="padding: 12px 28px;">Contact Us</a>
    </div>
  </div>
</section>
@endsection
