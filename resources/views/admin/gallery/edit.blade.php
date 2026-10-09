@extends('admin.layouts.app')

@section('title', 'Edit Gallery Item: ' . $item->title)
@section('header_title', 'Edit Gallery Item')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to Gallery
    </a>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header">
        <h2 class="card-title">Edit Gallery Media</h2>
    </div>

    <form method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="form-label">Item Title <span style="color: var(--accent-rose);">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                value="{{ old('title', $item->title) }}" 
                class="form-input" 
                style="width: 100%;" 
                required
            >
            @error('title')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Current Image</label>
            @if($item->image_path)
                <div style="margin-bottom: 12px;">
                    <img 
                        src="{{ asset('storage/' . $item->image_path) }}" 
                        alt="{{ $item->title }}" 
                        style="max-width: 200px; max-height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-subtle);"
                    >
                </div>
            @endif
            <label for="image" class="form-label" style="color: var(--text-muted);">Replace Image (leave empty to keep current)</label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                class="form-input" 
                style="width: 100%; padding: 8px;"
                accept=".jpg,.jpeg,.png,.webp"
            >
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 5px;">
                Supported formats: JPG, PNG, WEBP. Maximum file size: 5MB.
            </div>
            @error('image')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description / Caption</label>
            <textarea 
                id="description" 
                name="description" 
                class="form-textarea"
            >{{ old('description', $item->description) }}</textarea>
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
                    value="{{ old('sort_order', $item->sort_order) }}" 
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent-primary);">
                    Visible in public gallery
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
