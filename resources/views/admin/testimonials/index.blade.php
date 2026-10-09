@extends('admin.layouts.app')

@section('title', 'Manage Testimonials')
@section('header_title', 'Client Testimonials')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Testimonials &amp; Reviews</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Client endorsements, feedback ratings, and social proof.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            + Add Testimonial
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.testimonials.index') }}" class="filter-bar">
        <div class="filter-group">
            <label for="status" style="font-size: 13px; color: var(--text-muted);">Status:</label>
            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Testimonials</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Disabled Only</option>
            </select>
        </div>

        <div class="filter-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search client or position..." 
                class="form-input"
                style="width: 240px;"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Order</th>
                    <th>Client</th>
                    <th>Rating</th>
                    <th>Feedback Message</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $testimonial)
                    <tr>
                        <td style="font-weight: 700; color: var(--accent-primary);">
                            #{{ $testimonial->sort_order }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($testimonial->image_path)
                                    <img 
                                        src="{{ asset('storage/' . $testimonial->image_path) }}" 
                                        alt="{{ $testimonial->client_name }}" 
                                        style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-subtle);"
                                    >
                                @else
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: #fff;">
                                        {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 700; color: var(--text-primary);">{{ $testimonial->client_name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $testimonial->position ?? 'Verified Client' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: #fbbf24; font-size: 14px; letter-spacing: 1px;" title="{{ $testimonial->rating }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                        &#9733;
                                    @else
                                        <span style="color: var(--text-muted); opacity: 0.4;">&#9733;</span>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div style="max-width: 320px; font-size: 13px; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                &ldquo;{{ $testimonial->message }}&rdquo;
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $testimonial->is_active ? 'active' : 'inactive' }}">
                                {{ $testimonial->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $testimonial->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div class="table-actions" style="justify-content: flex-end;">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Delete testimonial from {{ addslashes($testimonial->client_name) }}?');" style="display: inline;">
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
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                </svg>
                                <h4>No testimonials found</h4>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if(request('search') || request('status'))
                                        No testimonials match your search or filter criteria. <a href="{{ route('admin.testimonials.index') }}" style="color: var(--accent-primary);">Clear filters</a> to view all.
                                    @else
                                        Collect feedback and add client testimonials using the button above.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($testimonials->hasPages())
        <div class="pagination-container">
            <div>Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} testimonials</div>
            <div>{{ $testimonials->links() }}</div>
        </div>
    @endif
</div>
@endsection
