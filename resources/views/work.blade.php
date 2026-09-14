@extends('layouts.app')

@section('title', 'Our Work — RK Vibes')

@section('content')
<section class="page-hero">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Our Work</div>
    <span class="eyebrow">Our Work</span>
    <h1>Recent proof, not promises.</h1>
    <p class="lead">A look at the events we've produced and the brands we've grown.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head"><span class="eyebrow">Featured</span><h2>Concert Night ft. Atif Aslam</h2></div>
    <div class="work-card" style="margin-bottom:70px;">
      <div class="thumb" style="height:220px;"><b>RK Vibes Presents — Concert Night</b></div>
      <div class="body" style="padding:30px;">
        <p style="color:var(--gray);font-size:15px;margin-bottom:20px;">One of RK Vibes' standout nights — a full-scale concert where the RK Vibes band opened and shared the stage with Atif Aslam. Handled end-to-end: venue production, promotion, ticketing, and the live run of show.</p>
        <div class="stats" style="border-top:1px solid var(--surface-line);padding-top:20px;">
          <div><b>1,000+</b><span>Attendees</span></div>
          <div><b>Sold Out</b><span>Ticket Status</span></div>
          <div><b>Full</b><span>Production &amp; Promo</span></div>
        </div>
      </div>
    </div>

    <div class="section-head"><span class="eyebrow">More Case Studies</span><h2>Across events, brands, and people.</h2></div>
    <div class="work-grid">
      <div class="work-card"><div class="thumb"><b>Music Event Series</b></div><div class="body"><span class="eyebrow">Events</span><h3>RK Vibes Live Series</h3><p>Multiple ticketed music nights produced and promoted end-to-end.</p><div class="stats"><div><b>6+</b><span>Events Run</span></div><div><b>High</b><span>Repeat Turnout</span></div></div></div></div>
      <div class="work-card"><div class="thumb"><b>Brand Growth</b></div><div class="body"><span class="eyebrow">Marketing</span><h3>Local Business Rebrand</h3><p>Positioning, content system and ad strategy for a growing local business.</p><div class="stats"><div><b>2.4x</b><span>Reach Growth</span></div><div><b>6wk</b><span>Engagement</span></div></div></div></div>
      <div class="work-card"><div class="thumb"><b>Personal Brand</b></div><div class="body"><span class="eyebrow">Personal Brand</span><h3>Founder Visibility Campaign</h3><p>Content and positioning strategy to build a founder's public presence.</p><div class="stats"><div><b>10k+</b><span>New Followers</span></div><div><b>4mo</b><span>Program Length</span></div></div></div></div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="wrap">
    <span class="eyebrow">Your Turn</span>
    <h2>Let's add your story to this list.</h2>
    <a href="{{ route('contact') }}" class="btn btn-solid">Start the Conversation</a>
  </div>
</section>
@endsection
