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
    @if($galleryItems->isNotEmpty())
      @php
        $featured = $galleryItems->first();
        $otherItems = $galleryItems->slice(1);
        $featuredUrl = null;
        if ($featured->image_path) {
            if (\Illuminate\Support\Str::startsWith($featured->image_path, ['http://', 'https://'])) {
                $featuredUrl = $featured->image_path;
            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($featured->image_path) || \Illuminate\Support\Str::startsWith($featured->image_path, 'gallery/')) {
                $featuredUrl = \Illuminate\Support\Facades\Storage::url($featured->image_path);
            } elseif (file_exists(public_path($featured->image_path))) {
                $featuredUrl = asset($featured->image_path);
            }
        }
      @endphp

      <div class="section-head"><span class="eyebrow">Featured</span><h2>{{ $featured->title }}</h2></div>
      <div class="work-card" style="margin-bottom:70px;">
        <div class="thumb" style="height:220px; position:relative; overflow:hidden;">
          @if($featuredUrl)
            <img src="{{ $featuredUrl }}" alt="{{ $featured->title }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <b>{{ $featured->title }}</b>
          @endif
        </div>
        <div class="body" style="padding:30px;">
          <p style="color:var(--gray);font-size:15px;margin-bottom:20px;">{{ $featured->description }}</p>
        </div>
      </div>

      @if($otherItems->isNotEmpty())
        <div class="section-head"><span class="eyebrow">More Case Studies</span><h2>Across events, brands, and people.</h2></div>
        <div class="work-grid">
          @foreach($otherItems as $item)
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
      @endif
    @else
      <div style="text-align:center; padding: 48px 24px; border: 1px dashed var(--surface-line); background: var(--surface); margin-bottom: 70px;">
        <p style="color: var(--muted); font-size: 15px;">No showcase gallery items available at this time.</p>
      </div>
    @endif
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
