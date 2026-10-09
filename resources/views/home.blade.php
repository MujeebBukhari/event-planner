@extends('layouts.app')

@section('title', 'Home — RK Vibes')

@section('content')
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow">Strategy — Marketing — Events — Growth</span>
      <h1>We Build Brands.<br>We Create <span>Experiences.</span></h1>
      <p class="lead">RK Vibes partners with businesses, brands and communities to plan the strategy, run the marketing, and produce the experience — end to end.</p>
      <div class="hero-actions">
        <a href="{{ route('work') }}" class="btn btn-ghost">See Our Work</a>
        <a href="{{ route('tickets') }}" class="btn btn-solid">Book Event Tickets</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><b>12+</b><span>Events Produced</span></div>
        <div class="stat"><b>3</b><span>Core Cities</span></div>
        <div class="stat"><b>4</b><span>Solution Pillars</span></div>
      </div>
    </div>

    <div class="hero-card">
      <span class="tag">Upcoming Event</span>
      <h3>RK Vibes Presents —<br>Fall Gathering 2026</h3>
      <p class="meta">Minneapolis, MN &nbsp;·&nbsp; <strong>Oct 2026</strong></p>
      <div class="ticket-row"><span class="name">General Admission</span><span class="price">$25</span></div>
      <div class="ticket-row"><span class="name">VIP Access</span><span class="price">$60</span></div>
      <a href="{{ route('tickets') }}" class="btn btn-solid">Book Now — Secure Checkout</a>
      <div class="secure-note">Paid direct via Stripe — no third-party ticketing fees</div>
    </div>
  </div>
</section>

<div class="perf"></div>

<section id="about">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">About RK Vibes</span>
      <h2>Not just an agency.<br>Not just an event company.</h2>
      <p>RK Vibes works across four disciplines — business strategy, marketing, live events, and revenue growth — so the people we work with get one partner instead of four vendors. Built on real events, real campaigns, and real results.</p>
      <a href="{{ route('about') }}" class="btn btn-ghost" style="margin-top:20px;">More About Us</a>
    </div>
  </div>
</section>

<div class="perf"></div>

<section id="solutions">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">What We Do</span>
      <h2>Four ways we grow you.</h2>
    </div>
  </div>
  <div class="wrap" style="padding:0 32px;">
    @if($services->isNotEmpty())
      <div class="pillars">
        @foreach($services as $service)
          <div class="pillar">
            <span class="num">{{ sprintf('%02d', $loop->iteration) }} — {{ $service->icon ?: 'Pillar' }}</span>
            <h3>{{ $service->title }}</h3>
            <p>{{ $service->description }}</p>
          </div>
        @endforeach
      </div>
    @else
      <div style="text-align:center; padding: 40px 20px; border: 1px dashed var(--surface-line); background: var(--bg-alt);">
        <p style="color: var(--muted); font-size: 14px;">No active services currently listed.</p>
      </div>
    @endif
  </div>
</section>

<div class="perf"></div>

<section id="work">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Our Work</span>
      <h2>Recent proof, not promises.</h2>
      <p>A look at what we've produced and grown — for our own events and for the people we work with.</p>
    </div>
    @if($galleryItems->isNotEmpty())
      <div class="work-grid">
        @foreach($galleryItems->take(3) as $item)
          @php
            $itemUrl = null;
            if ($item->image_path) {
                if (\Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://'])) {
                    $itemUrl = $item->image_path;
                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($item->image_path) || \Illuminate\Support\Str::startsWith($item->image_path, 'gallery/')) {
                    $itemUrl = \Illuminate\Support\Facades\Storage::url($item->image_path);
                } elseif (file_exists(public_path($item->image_path))) {
                    $itemUrl = asset($item->image_path);
                }
            }
          @endphp
          <div class="work-card">
            <div class="thumb" style="position:relative; overflow:hidden;">
              @if($itemUrl)
                <img src="{{ $itemUrl }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
              @else
                <b>{{ $item->title }}</b>
              @endif
            </div>
            <div class="body">
              <span class="eyebrow">Showcase</span>
              <h3>{{ $item->title }}</h3>
              <p>{{ $item->description }}</p>
            </div>
          </div>
        @endforeach
      </div>
      <a href="{{ route('work') }}" class="btn btn-ghost" style="margin-top:34px;">View All Work</a>
    @else
      <div style="text-align:center; padding: 40px 20px; border: 1px dashed var(--surface-line); background: var(--surface);">
        <p style="color: var(--muted); font-size: 14px;">No showcase gallery items available at this time.</p>
      </div>
    @endif
  </div>
</section>

@if($testimonials->isNotEmpty())
<div class="perf"></div>

<section id="testimonials">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Client Feedback</span>
      <h2>What people say.</h2>
      <p>Stories from the organizers, businesses, and founders we've partnered with.</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
      @foreach($testimonials as $testimonial)
        @php
          $avatarUrl = null;
          if ($testimonial->image_path) {
              if (\Illuminate\Support\Str::startsWith($testimonial->image_path, ['http://', 'https://'])) {
                  $avatarUrl = $testimonial->image_path;
              } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($testimonial->image_path) || \Illuminate\Support\Str::startsWith($testimonial->image_path, 'testimonials/')) {
                  $avatarUrl = \Illuminate\Support\Facades\Storage::url($testimonial->image_path);
              } elseif (file_exists(public_path($testimonial->image_path))) {
                  $avatarUrl = asset($testimonial->image_path);
              }
          }
        @endphp
        <div style="background: var(--surface); border: 1px solid var(--surface-line); padding: 26px; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="color: var(--gold); font-size: 14px; margin-bottom: 12px; letter-spacing: 2px;">
              {{ str_repeat('★', max(1, min(5, (int) $testimonial->rating))) }}
            </div>
            <p style="color: var(--ink); font-style: italic; font-size: 14.5px; line-height: 1.55; margin-bottom: 18px;">
              "{{ $testimonial->message }}"
            </p>
          </div>
          <div style="display: flex; align-items: center; gap: 12px; border-top: 1px solid var(--surface-line); padding-top: 14px;">
            @if($avatarUrl)
              <img src="{{ $avatarUrl }}" alt="{{ $testimonial->client_name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--gold);">
            @endif
            <div>
              <b style="display: block; font-size: 13.5px; color: var(--ink);">{{ $testimonial->client_name }}</b>
              @if($testimonial->position)
                <span style="font-size: 11.5px; color: var(--muted);">{{ $testimonial->position }}</span>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="audience">
  <div class="wrap"><div class="section-head"><span class="eyebrow">Who You Are</span><h2>Tell us what you're building.</h2></div></div>
  <div class="wrap" style="padding:0 32px;">
    <div class="audience-grid">
      <div class="audience-card"><span class="eyebrow">For Businesses</span><h3>Growing a Business</h3><p>Strategy, marketing and revenue support to grow what you've already built.</p><a href="{{ route('contact') }}" class="go">Request an Assessment &rarr;</a></div>
      <div class="audience-card"><span class="eyebrow">For Hosts</span><h3>Planning an Event</h3><p>End-to-end production, promotion and ticketing for your next event.</p><a href="{{ route('contact') }}" class="go">Let's Build Your Event &rarr;</a></div>
      <div class="audience-card"><span class="eyebrow">For Individuals</span><h3>Building a Personal Brand</h3><p>Positioning and content strategy to build your public presence.</p><a href="{{ route('contact') }}" class="go">Start Your Brand &rarr;</a></div>
    </div>
  </div>
</section>

<section id="insights">
  <div class="wrap">
    <div class="section-head"><span class="eyebrow">Insights</span><h2>Notes on growth, brand and events.</h2></div>
    <div class="insight-grid">
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Marketing</span><h4>Three signs your brand is ready for a rebrand</h4><p>How to tell when your visual identity is holding your business back.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Events</span><h4>Selling tickets direct vs. third-party platforms</h4><p>What changes when you own the checkout instead of renting it.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Strategy</span><h4>Turning one event into a year-round business</h4><p>How live experiences feed a bigger growth strategy.</p></div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="wrap">
    <span class="eyebrow">Get In Touch</span>
    <h2>Let's plan your next move.</h2>
    <a href="{{ route('contact') }}" class="btn btn-solid">Start the Conversation</a>
  </div>
</section>
@endsection
