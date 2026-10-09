@extends('layouts.app')

@section('title', '404 — Page Not Found | RK Vibes')

@section('content')
<section class="page-hero" style="text-align: center; padding: 100px 0 120px;">
  <div class="wrap" style="max-width: 700px; margin: 0 auto;">
    <span class="eyebrow" style="color: var(--gold); letter-spacing: 0.15em;">Error 404</span>
    <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin: 16px 0;">Page Not Found</h1>
    <p class="lead" style="color: rgba(255, 255, 255, 0.7); font-size: 1.15rem; margin-bottom: 35px;">
      The page you are looking for may have been moved, renamed, or does not exist.
    </p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="{{ route('home') }}" class="btn btn-solid" style="padding: 12px 28px;">Return to Homepage</a>
      <a href="{{ route('contact') }}" class="btn btn-outline" style="padding: 12px 28px;">Contact Us</a>
    </div>
  </div>
</section>
@endsection
