@extends('layouts.app')

@section('title', 'Contact — RK Vibes')

@section('content')
<section class="page-hero">
  <div class="wrap">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / Contact</div>
    <span class="eyebrow">Get In Touch</span>
    <h1>Let's plan your next move.</h1>
    <p class="lead">Tell us a bit about what you're building and we'll follow up within one business day.</p>
  </div>
</section>

<section style="padding-top:50px;">
  <div class="wrap">
    @if (session('success'))
      <div style="background: rgba(215, 174, 48, 0.12); border: 1px solid var(--gold); color: var(--gold); padding: 16px 22px; margin-bottom: 30px; font-weight: 600; font-size: 14.5px;">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #ff8080; padding: 16px 22px; margin-bottom: 30px; font-size: 14px;">
        <strong style="display:block;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.04em;">Please correct the following:</strong>
        <ul style="padding-left: 20px; margin: 0;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="two-col">
      <div class="panel">
        <h3>Send a Message</h3>
        <form action="{{ route('contact.store') }}" method="POST">
          @csrf
          <div class="field">
            <label>I'm interested in...</label>
            <select name="subject">
              <option value="Growing a Business" {{ old('subject') === 'Growing a Business' ? 'selected' : '' }}>Growing a Business</option>
              <option value="Planning an Event" {{ old('subject') === 'Planning an Event' ? 'selected' : '' }}>Planning an Event</option>
              <option value="Building a Personal Brand" {{ old('subject') === 'Building a Personal Brand' ? 'selected' : '' }}>Building a Personal Brand</option>
              <option value="Something Else" {{ old('subject') === 'Something Else' ? 'selected' : '' }}>Something Else</option>
            </select>
          </div>
          <div class="field-row">
            <div class="field">
              <label>Full Name</label>
              <input type="text" name="name" placeholder="Jordan Smith" value="{{ old('name') }}" required>
            </div>
            <div class="field">
              <label>Email</label>
              <input type="email" name="email" placeholder="jordan@email.com" value="{{ old('email') }}" required>
            </div>
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="(612) 000-0000" value="{{ old('phone') }}">
          </div>
          <div class="field">
            <label>Tell us about your project</label>
            <input type="text" name="message" placeholder="A few lines about what you're building..." style="height:90px;" value="{{ old('message') }}" required>
          </div>
          <button type="submit" class="btn btn-solid" style="width:100%;justify-content:center;margin-top:10px;">Send Message</button>
        </form>
      </div>

      <div class="copy-block">
        <h3>Direct Contact</h3>
        <p>Prefer to reach out directly? Email or call and we'll get back to you personally.</p>
        <p><strong style="color:var(--ink);">Email:</strong> hello@rkvibes.com</p>
        <p><strong style="color:var(--ink);">Phone:</strong> (612) 000-0000</p>
        <p><strong style="color:var(--ink);">Based in:</strong> Minneapolis, MN</p>
        <h3>Follow Along</h3>
        <p>See the latest events and behind-the-scenes on social.</p>
        <p><strong style="color:var(--ink);">Instagram:</strong> @rkvibes</p>
      </div>
    </div>
  </div>
</section>
@endsection
