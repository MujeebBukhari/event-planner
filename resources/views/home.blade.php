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
    <div class="pillars">
      <div class="pillar"><span class="num">01 — Strategy</span><h3>Business &amp; Market Strategy</h3><p>Positioning, planning and a clear growth roadmap before a single ad goes live.</p></div>
      <div class="pillar"><span class="num">02 — Marketing</span><h3>Marketing &amp; Brand Growth</h3><p>Content, ads and brand-building that get a business found and remembered.</p></div>
      <div class="pillar"><span class="num">03 — Events</span><h3>Events &amp; Experiences</h3><p>Concept to execution — production, promotion, and the night itself.</p></div>
      <div class="pillar"><span class="num">04 — Revenue</span><h3>Revenue &amp; Sales Growth</h3><p>Turning attention into bookings, ticket sales, and repeat business.</p></div>
    </div>
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
    <div class="work-grid">
      <div class="work-card">
        <div class="thumb"><b>Live Concert Night<br>ft. Atif Aslam</b></div>
        <div class="body">
          <span class="eyebrow">Case Study</span>
          <h3>RK Vibes Presents — Concert Night</h3>
          <p>Full production and promotion for a headline concert, with the RK Vibes band sharing the stage with Atif Aslam.</p>
          <div class="stats"><div><b>1,000+</b><span>Attendees</span></div><div><b>Sold Out</b><span>Ticket Status</span></div></div>
        </div>
      </div>
      <div class="work-card">
        <div class="thumb"><b>Brand Growth</b></div>
        <div class="body">
          <span class="eyebrow">Case Study</span>
          <h3>Local Business Rebrand</h3>
          <p>Positioning, content system and ad strategy for a growing local business.</p>
          <div class="stats"><div><b>2.4x</b><span>Reach Growth</span></div><div><b>6wk</b><span>Engagement</span></div></div>
        </div>
      </div>
      <div class="work-card">
        <div class="thumb"><b>Personal Brand</b></div>
        <div class="body">
          <span class="eyebrow">Case Study</span>
          <h3>Founder Visibility Campaign</h3>
          <p>Content and positioning strategy to build a founder's public presence.</p>
          <div class="stats"><div><b>10k+</b><span>New Followers</span></div><div><b>4mo</b><span>Program Length</span></div></div>
        </div>
      </div>
    </div>
    <a href="{{ route('work') }}" class="btn btn-ghost" style="margin-top:34px;">View All Work</a>
  </div>
</section>

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
