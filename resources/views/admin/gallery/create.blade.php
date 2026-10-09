@extends('admin.layouts.app')

@section('title', 'Add Gallery Item')
@section('header_title', 'Upload Gallery Image')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to Gallery
    </a>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header">
        <h2 class="card-title">New Gallery Media</h2>
    </div>

    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title" class="form-label">Item Title <span style="color: var(--accent-rose);">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                value="{{ old('title') }}" 
                class="form-input" 
                style="width: 100%;" 
                placeholder="e.g. Concert Night Stage Setup"
                required
            >
            @error('title')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Upload Image <span style="color: var(--accent-rose);">*</span></label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                class="form-input" 
                style="width: 100%; padding: 8px;"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 5px;">
                Supported formats: JPG, PNG, WEBP. Maximum file size: 5MB.
            </div>
            @error('image')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description / Caption (optional)</label>
            <textarea 
                id="description" 
                name="description" 
                class="form-textarea" 
                placeholder="Brief description of the event or photo..."
            >{{ old('description') }}</textarea>
            @error('description')
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
                    Visible in public gallery
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Upload &amp; Save</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
