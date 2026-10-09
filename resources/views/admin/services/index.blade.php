@extends('admin.layouts.app')

@section('title', 'Manage Services')
@section('header_title', 'Services')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Services &amp; Offerings</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Configure and prioritize the services displayed to clients.</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            + Add New Service
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.services.index') }}" class="filter-bar">
        <div class="filter-group">
            <label for="status" style="font-size: 13px; color: var(--text-muted);">Status:</label>
            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Services</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Disabled Only</option>
            </select>
        </div>

        <div class="filter-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search service title..." 
                class="form-input"
                style="width: 240px;"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Order</th>
                    <th>Title &amp; Slug</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td style="font-weight: 700; color: var(--accent-primary);">
                            #{{ $service->sort_order }}
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-primary);">{{ $service->title }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">/{{ $service->slug }}</div>
                        </td>
                        <td>
                            <div style="max-width: 320px; font-size: 13px; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $service->description ?? 'No description provided' }}
                            </div>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge badge-{{ $service->is_active ? 'active' : 'inactive' }}" style="cursor: pointer; background-color: transparent;" title="Click to toggle status">
                                    {{ $service->is_active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $service->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div class="table-actions" style="justify-content: flex-end;">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Are you sure you want to delete service \'{{ addslashes($service->title) }}\'?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                                <h4>No services found</h4>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if(request('search') || request('status'))
                                        No services match your search or filter criteria. <a href="{{ route('admin.services.index') }}" style="color: var(--accent-primary);">Clear filters</a> to view all.
                                    @else
                                        Add your first service pillar using the button above.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($services->hasPages())
        <div class="pagination-container">
            <div>Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} services</div>
            <div>{{ $services->links() }}</div>
        </div>
    @endif
</div>
@endsection
