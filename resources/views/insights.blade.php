@extends('layouts.app')

@section('title', 'Insights — RK Vibes')

@section('content')
<section class="page-hero">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Insights</div>
    <span class="eyebrow">Insights</span>
    <h1>Notes on growth, brand, and events.</h1>
    <p class="lead">Thinking from the RK Vibes team on strategy, marketing, and running live experiences.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="insight-grid">
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Marketing</span><h4>Three signs your brand is ready for a rebrand</h4><p>How to tell when your visual identity is holding your business back.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Events</span><h4>Selling tickets direct vs. third-party platforms</h4><p>What changes when you own the checkout instead of renting it.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Strategy</span><h4>Turning one event into a year-round business</h4><p>How live experiences feed a bigger growth strategy.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Revenue</span><h4>What sponsors actually want from an event</h4><p>Beyond the logo on a banner — building sponsorships that renew.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Personal Brand</span><h4>Building a public presence without losing focus</h4><p>A simple content system for founders who don't have time to post daily.</p></div>
      <div class="insight-card"><div class="thumb"></div><span class="eyebrow">Marketing</span><h4>The ad budget mistake most small businesses make</h4><p>Spending on reach before nailing the offer.</p></div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="wrap">
    <span class="eyebrow">Have a Project?</span>
    <h2>Let's put these ideas to work.</h2>
    <a href="{{ route('contact') }}" class="btn btn-solid">Start the Conversation</a>
  </div>
</section>
@endsection
