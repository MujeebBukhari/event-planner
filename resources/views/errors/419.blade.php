@extends('layouts.app')

@section('title', '419 — Page Expired | RK Vibes')

@section('content')
<section class="page-hero" style="text-align: center; padding: 100px 0 120px;">
  <div class="wrap" style="max-width: 700px; margin: 0 auto;">
    <span class="eyebrow" style="color: var(--gold); letter-spacing: 0.15em;">Error 419</span>
    <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin: 16px 0;">Session Expired</h1>
    <p class="lead" style="color: rgba(255, 255, 255, 0.7); font-size: 1.15rem; margin-bottom: 35px;">
      Your security token has expired due to inactivity. Please refresh the page and try submitting your form again.
    </p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="javascript:location.reload()" class="btn btn-solid" style="padding: 12px 28px;">Refresh Page</a>
      <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 12px 28px;">Return to Homepage</a>
    </div>
  </div>
</section>
@endsection
