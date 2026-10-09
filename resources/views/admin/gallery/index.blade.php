@extends('admin.layouts.app')

@section('title', 'Manage Gallery')
@section('header_title', 'Gallery Items')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Gallery &amp; Showcase Media</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Upload and arrange images for past event highlights and portfolios.</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
            + Add Gallery Item
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.gallery.index') }}" class="filter-bar">
        <div class="filter-group">
            <label for="status" style="font-size: 13px; color: var(--text-muted);">Status:</label>
            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Items</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Disabled Only</option>
            </select>
        </div>

        <div class="filter-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search gallery title..." 
                class="form-input"
                style="width: 240px;"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Order</th>
                    <th style="width: 90px;">Thumbnail</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Uploaded</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="font-weight: 700; color: var(--accent-primary);">
                            #{{ $item->sort_order }}
                        </td>
                        <td>
                            @if($item->image_path)
                                <img 
                                    src="{{ asset('storage/' . $item->image_path) }}" 
                                    alt="{{ $item->title }}" 
                                    style="width: 64px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-subtle); background-color: var(--bg-surface-elevated);"
                                    onerror="this.style.display='none'"
                                >
                            @else
                                <div style="width: 64px; height: 48px; border-radius: 6px; background: var(--bg-surface-elevated); display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--text-muted);">No Pic</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-primary);">{{ $item->title }}</div>
                        </td>
                        <td>
                            <div style="max-width: 280px; font-size: 13px; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $item->description ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $item->is_active ? 'active' : 'inactive' }}">
                                {{ $item->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $item->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div class="table-actions" style="justify-content: flex-end;">
                                <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete gallery item \'{{ addslashes($item->title) }}\'?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <h4>No gallery items found</h4>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if(request('search') || request('status'))
                                        No gallery items match your search or filter criteria. <a href="{{ route('admin.gallery.index') }}" style="color: var(--accent-primary);">Clear filters</a> to view all.
                                    @else
                                        Upload your first event image to show off your work.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="pagination-container">
            <div>Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} items</div>
            <div>{{ $items->links() }}</div>
        </div>
    @endif
</div>
@endsection
