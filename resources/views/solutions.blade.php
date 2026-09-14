@extends('layouts.app')

@section('title', 'Solutions — RK Vibes')

@section('content')
<section class="page-hero">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Solutions</div>
    <span class="eyebrow">What We Do</span>
    <h1>Four ways we grow you.</h1>
    <p class="lead">Pick where you need help most — or let us run the full picture from strategy through execution.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="pillars" style="margin-bottom:70px;">
      <div class="pillar">
        <span class="num">01 — Strategy</span>
        <h3>Business &amp; Market Strategy</h3>
        <p>Positioning, planning and a clear growth roadmap before a single ad goes live.</p>
        <ul><li>Market &amp; competitor analysis</li><li>Positioning &amp; messaging</li><li>Growth roadmap</li></ul>
      </div>
      <div class="pillar">
        <span class="num">02 — Marketing</span>
        <h3>Marketing &amp; Brand Growth</h3>
        <p>Content, ads and brand-building that get a business found and remembered.</p>
        <ul><li>Content &amp; video production</li><li>Paid ad campaigns</li><li>Brand identity &amp; design</li></ul>
      </div>
      <div class="pillar">
        <span class="num">03 — Events</span>
        <h3>Events &amp; Experiences</h3>
        <p>Concept to execution — production, promotion, and the night itself.</p>
        <ul><li>Event concept &amp; production</li><li>Talent booking</li><li>Ticketing &amp; on-site run</li></ul>
      </div>
      <div class="pillar">
        <span class="num">04 — Revenue</span>
        <h3>Revenue &amp; Sales Growth</h3>
        <p>Turning attention into bookings, ticket sales, and repeat business.</p>
        <ul><li>Sales funnel design</li><li>Ticket &amp; sponsorship revenue</li><li>Retention &amp; repeat business</li></ul>
      </div>
    </div>
  </div>
</section>

<section class="audience">
  <div class="wrap"><div class="section-head"><span class="eyebrow">Not Sure Where to Start?</span><h2>Tell us what you're building.</h2></div></div>
  <div class="wrap" style="padding:0 32px;">
    <div class="audience-grid">
      <div class="audience-card"><span class="eyebrow">For Businesses</span><h3>Growing a Business</h3><p>Strategy, marketing and revenue support to grow what you've already built.</p><a href="{{ route('contact') }}" class="go">Request an Assessment &rarr;</a></div>
      <div class="audience-card"><span class="eyebrow">For Hosts</span><h3>Planning an Event</h3><p>End-to-end production, promotion and ticketing for your next event.</p><a href="{{ route('contact') }}" class="go">Let's Build Your Event &rarr;</a></div>
      <div class="audience-card"><span class="eyebrow">For Individuals</span><h3>Building a Personal Brand</h3><p>Positioning and content strategy to build your public presence.</p><a href="{{ route('contact') }}" class="go">Start Your Brand &rarr;</a></div>
    </div>
  </div>
</section>
@endsection
