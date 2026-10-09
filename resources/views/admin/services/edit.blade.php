@extends('admin.layouts.app')

@section('title', 'Edit Service: ' . $service->title)
@section('header_title', 'Edit Service')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to Services
    </a>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-header">
        <h2 class="card-title">Edit Service Pillar</h2>
    </div>

    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="form-label">Service Title <span style="color: var(--accent-rose);">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                value="{{ old('title', $service->title) }}" 
                class="form-input" 
                style="width: 100%;" 
                required
            >
            @error('title')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="slug" class="form-label">URL Slug</label>
                <input 
                    type="text" 
                    id="slug" 
                    name="slug" 
                    value="{{ old('slug', $service->slug) }}" 
                    class="form-input" 
                    style="width: 100%;"
                >
                @error('slug')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="icon" class="form-label">Icon / Tag</label>
                <input 
                    type="text" 
                    id="icon" 
                    name="icon" 
                    value="{{ old('icon', $service->icon) }}" 
                    class="form-input" 
                    style="width: 100%;"
                >
                @error('icon')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea 
                id="description" 
                name="description" 
                class="form-textarea"
            >{{ old('description', $service->description) }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order" class="form-label">Display Order</label>
                <input 
                    type="number" 
                    id="sort_order" 
                    name="sort_order" 
                    value="{{ old('sort_order', $service->sort_order) }}" 
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--accent-primary);">
                    Active (visible publicly)
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Update Service</button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
