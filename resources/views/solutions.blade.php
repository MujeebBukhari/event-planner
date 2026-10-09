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
    @if($services->isNotEmpty())
      <div class="pillars" style="margin-bottom:70px;">
        @foreach($services as $service)
          <div class="pillar">
            <span class="num">{{ sprintf('%02d', $loop->iteration) }} — {{ $service->icon ?: 'Pillar' }}</span>
            <h3>{{ $service->title }}</h3>
            <p>{{ $service->description }}</p>
          </div>
        @endforeach
      </div>
    @else
      <div style="text-align:center; padding: 48px 24px; border: 1px dashed var(--surface-line); background: var(--bg-alt); margin-bottom: 70px;">
        <p style="color: var(--muted); font-size: 15px;">No active services currently listed. Please check back soon.</p>
      </div>
    @endif
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
