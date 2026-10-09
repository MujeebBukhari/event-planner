@extends('admin.layouts.app')

@section('title', 'Edit Testimonial: ' . $testimonial->client_name)
@section('header_title', 'Edit Testimonial')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to Testimonials
    </a>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header">
        <h2 class="card-title">Edit Client Testimonial</h2>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="client_name" class="form-label">Client Name <span style="color: var(--accent-rose);">*</span></label>
                <input 
                    type="text" 
                    id="client_name" 
                    name="client_name" 
                    value="{{ old('client_name', $testimonial->client_name) }}" 
                    class="form-input" 
                    style="width: 100%;" 
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
                    value="{{ old('position', $testimonial->position) }}" 
                    class="form-input" 
                    style="width: 100%;"
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
                    <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars &mdash; Excellent (&#9733;&#9733;&#9733;&#9733;&#9733;)</option>
                    <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars &mdash; Very Good (&#9733;&#9733;&#9733;&#9733;)</option>
                    <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars &mdash; Average (&#9733;&#9733;&#9733;)</option>
                    <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars &mdash; Fair (&#9733;&#9733;)</option>
                    <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star &mdash; Poor (&#9733;)</option>
                </select>
                @error('rating')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Client Photo</label>
                @if($testimonial->image_path)
                    <div style="margin-bottom: 8px;">
                        <img 
                            src="{{ asset('storage/' . $testimonial->image_path) }}" 
                            alt="{{ $testimonial->client_name }}" 
                            style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-subtle);"
                        >
                    </div>
                @endif
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
                required
            >{{ old('message', $testimonial->message) }}</textarea>
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
                    value="{{ old('sort_order', $testimonial->sort_order) }}" 
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent-primary);">
                    Visible in public testimonials
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
