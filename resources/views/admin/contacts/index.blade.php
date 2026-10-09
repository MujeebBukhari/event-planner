@extends('admin.layouts.app')

@section('title', 'Contact Messages')
@section('header_title', 'Contact Inquiries')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Inquiries &amp; Messages</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Public contact form messages submitted by visitors and clients.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="filter-bar">
        <div class="filter-group">
            <label for="status" style="font-size: 13px; color: var(--text-muted);">Status:</label>
            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Messages</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
            </select>
        </div>

        <div class="filter-group">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search name, email, subject..." 
                class="form-input"
                style="width: 260px;"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>
    </form>

    <!-- Contacts Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sender Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Received At</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $messageItem)
                    <tr style="{{ $messageItem->status === 'unread' ? 'background-color: rgba(59, 130, 246, 0.04);' : '' }}">
                        <td style="font-weight: 700; color: var(--text-muted);">#{{ $messageItem->id }}</td>
                        <td>
                            <span style="font-weight: {{ $messageItem->status === 'unread' ? '700' : '500' }}; color: var(--text-primary);">
                                {{ $messageItem->name }}
                            </span>
                        </td>
                        <td>{{ $messageItem->email }}</td>
                        <td>{{ $messageItem->phone ?? '—' }}</td>
                        <td>
                            <div style="font-weight: {{ $messageItem->status === 'unread' ? '600' : '400' }}; max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $messageItem->subject ?? 'General Inquiry' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $messageItem->status }}">
                                {{ $messageItem->status }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $messageItem->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div class="table-actions" style="justify-content: flex-end;">
                                <a href="{{ route('admin.contacts.show', $messageItem) }}" class="btn btn-secondary btn-sm">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.contacts.status', $messageItem) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $messageItem->status === 'unread' ? 'read' : 'unread' }}">
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Toggle read/unread">
                                        {{ $messageItem->status === 'unread' ? 'Mark Read' : 'Mark Unread' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.contacts.destroy', $messageItem) }}" onsubmit="return confirm('Delete message #{{ $messageItem->id }} from {{ $messageItem->name }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <h4>No contact messages found</h4>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if(request('search') || request('status'))
                                        No messages match your search or filter criteria. <a href="{{ route('admin.contacts.index') }}" style="color: var(--accent-primary);">Clear filters</a> to view all.
                                    @else
                                        No contact inquiries have been submitted yet.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div class="pagination-container">
            <div>Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }} results</div>
            <div>{{ $messages->links() }}</div>
        </div>
    @endif
</div>
@endsection
