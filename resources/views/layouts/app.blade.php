<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'RK Vibes — Strategy. Brand. Experience.')</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@stack('styles')
</head>
<body>
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
      <div class="nav-links-mobile-actions">
        @auth
          <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-ghost mobile-nav-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Dashboard
          </a>
        @endauth
        <a href="{{ route('tickets') }}" class="btn btn-sm btn-solid mobile-nav-btn">Book Tickets</a>
      </div>
    </div>
    <div class="nav-actions">
      @auth
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-ghost nav-login" title="Admin Dashboard">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span>Dashboard</span>
        </a>
      @endauth
      <a href="{{ route('tickets') }}" class="btn btn-solid nav-cta">Book Tickets</a>
      <button class="mobile-toggle" aria-label="Toggle navigation">&#9776;</button>
    </div>
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
        @auth
          <a href="{{ route('admin.dashboard') }}">Staff Portal</a>
        @else
          <a href="{{ route('login') }}">Staff Login</a>
        @endauth
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2026 RK Vibes. All rights reserved.</span>
      <div class="footer-bottom-links">
        @auth
          <a href="{{ route('admin.dashboard') }}">Portal</a>
        @else
          <a href="{{ route('login') }}">Login</a>
        @endauth
        <span class="sep">•</span>
        <span>Minneapolis, MN</span>
      </div>
    </div>
  </div>
</footer>

<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
