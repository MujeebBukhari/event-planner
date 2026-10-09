@extends('admin.layouts.app')

@section('title', 'Add Testimonial')
@section('header_title', 'Create Testimonial')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to Testimonials
    </a>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header">
        <h2 class="card-title">New Client Testimonial</h2>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="client_name" class="form-label">Client Name <span style="color: var(--accent-rose);">*</span></label>
                <input 
                    type="text" 
                    id="client_name" 
                    name="client_name" 
                    value="{{ old('client_name') }}" 
                    class="form-input" 
                    style="width: 100%;" 
                    placeholder="e.g. Sarah Jenkins"
                    required
                >
                @error('client_name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="position" class="form-label">Position / Company (optional)</label>
                <input 
                    type="text" 
                    id="position" 
                    name="position" 
                    value="{{ old('position') }}" 
                    class="form-input" 
                    style="width: 100%;" 
                    placeholder="e.g. Director, Acme Gala"
                >
                @error('position')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="rating" class="form-label">Rating (1 to 5 Stars) <span style="color: var(--accent-rose);">*</span></label>
                <select name="rating" id="rating" class="form-select" style="width: 100%;" required>
                    <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>5 Stars &mdash; Excellent (&#9733;&#9733;&#9733;&#9733;&#9733;)</option>
                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars &mdash; Very Good (&#9733;&#9733;&#9733;&#9733;)</option>
                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars &mdash; Average (&#9733;&#9733;&#9733;)</option>
                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars &mdash; Fair (&#9733;&#9733;)</option>
                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star &mdash; Poor (&#9733;)</option>
                </select>
                @error('rating')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Client Avatar / Photo (optional)</label>
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    class="form-input" 
                    style="width: 100%; padding: 8px;"
                    accept=".jpg,.jpeg,.png,.webp"
                >
                @error('image')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="message" class="form-label">Testimonial Message <span style="color: var(--accent-rose);">*</span></label>
            <textarea 
                id="message" 
                name="message" 
                class="form-textarea" 
                placeholder="What did the client say about working with RK Vibes?"
                required
            >{{ old('message') }}</textarea>
            @error('message')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order" class="form-label">Sort Order</label>
                <input 
                    type="number" 
                    id="sort_order" 
                    name="sort_order" 
                    value="{{ old('sort_order', 0) }}" 
                    class="form-input" 
                    style="width: 100%;" 
                    min="0"
                >
                @error('sort_order')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; padding-top: 28px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--text-primary); font-size: 13px; font-weight: 600;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent-primary);">
                    Visible in public testimonials
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save Testimonial</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
