@extends('layouts.app')

@section('title', 'About — RK Vibes')

@section('content')
<section class="page-hero">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / About</div>
    <span class="eyebrow">About RK Vibes</span>
    <h1>One team. Four disciplines. One outcome — growth.</h1>
    <p class="lead">RK Vibes started as an events company and grew into something broader: a partner for strategy, marketing, live experiences, and revenue — for businesses, brands, and individuals alike.</p>
  </div>
</section>

<section>
  <div class="wrap two-col">
    <div class="copy-block">
      <h3>Where we started</h3>
      <p>RK Vibes built its name producing live music events — booking talent, selling tickets, and packing rooms. That hands-on experience running real events, real budgets, and real crowds is still the foundation of everything else we do.</p>
      <h3>Where we're going</h3>
      <p>As the business grew, so did what people asked of us: help positioning a company, running its marketing, building a founder's personal brand, not just producing their event. Today RK Vibes operates as a full growth partner — strategy, marketing, events, and revenue, under one roof.</p>
      <div class="quote-block">
        <p>"We don't hand off to a different vendor for every piece. The same team that builds your strategy runs your campaign and produces your event."</p>
        <span>— RK Vibes, Founder</span>
      </div>
    </div>
    <div class="copy-block">
      <h3>How we work</h3>
      <p>Every engagement follows the same growth framework, whether it's a business consulting project or a full-scale event:</p>
      <div class="framework">
        <div><span class="step-num">01</span><div class="step-name">Analyze</div></div>
        <div><span class="step-num">02</span><div class="step-name">Strategize</div></div>
        <div><span class="step-num">03</span><div class="step-name">Create</div></div>
        <div><span class="step-num">04</span><div class="step-name">Execute</div></div>
        <div><span class="step-num">05</span><div class="step-name">Measure</div></div>
        <div><span class="step-num">06</span><div class="step-name">Grow</div></div>
      </div>
      <div class="stat-strip">
        <div><b>12+</b><span>Events Produced</span></div>
        <div><b>4</b><span>Solution Pillars</span></div>
        <div><b>2026</b><span>Next Chapter</span></div>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="wrap">
    <span class="eyebrow">Work With Us</span>
    <h2>Let's see if we're a fit.</h2>
    <a href="{{ route('contact') }}" class="btn btn-solid">Start the Conversation</a>
  </div>
</section>
@endsection
