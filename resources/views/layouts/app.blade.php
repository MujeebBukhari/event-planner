<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'RK Vibes — Strategy. Brand. Experience.')</title>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@stack('styles')
</head>
<body>
<div class="demo-banner">LAYOUT DEMO — placeholder copy &amp; current logo, real branding drops in next pass</div>
<header>
  <nav>
    <a href="{{ route('home') }}" class="logo-mark"><img src="{{ asset('assets/images/logo.png') }}" alt="RK Vibes logo"></a>
    <div class="nav-links">
      <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
      <a href="{{ route('solutions') }}" class="{{ request()->routeIs('solutions') ? 'active' : '' }}">Solutions</a>
      <a href="{{ route('work') }}" class="{{ request()->routeIs('work') ? 'active' : '' }}">Our Work</a>
      <a href="{{ route('tickets') }}" class="{{ request()->routeIs('tickets') ? 'active' : '' }}">Events</a>
      <a href="{{ route('insights') }}" class="{{ request()->routeIs('insights') ? 'active' : '' }}">Insights</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </div>
    <a href="{{ route('tickets') }}" class="btn btn-solid nav-cta">Book Tickets</a>
    <button class="mobile-toggle" aria-label="Toggle navigation">&#9776;</button>
  </nav>
</header>

@yield('content')

<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <img src="{{ asset('assets/images/logo.png') }}" alt="RK Vibes logo">
        <p>Business strategy, marketing, and live experiences — under one roof.</p>
      </div>
      <div class="footer-col">
        <h5>Company</h5>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('solutions') }}">Solutions</a>
        <a href="{{ route('work') }}">Our Work</a>
      </div>
      <div class="footer-col">
        <h5>Events</h5>
        <a href="{{ route('tickets') }}">Upcoming Events</a>
        <a href="{{ route('tickets') }}">Book Tickets</a>
      </div>
      <div class="footer-col">
        <h5>Connect</h5>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('insights') }}">Insights</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2026 RK Vibes. All rights reserved.</span>
      <span>Minneapolis, MN</span>
    </div>
  </div>
</footer>

<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
