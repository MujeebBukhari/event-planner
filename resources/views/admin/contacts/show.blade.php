@extends('admin.layouts.app')

@section('title', 'Message #' . $contact->id . ' from ' . $contact->name)
@section('header_title', 'Inquiry #' . $contact->id)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
        &larr; Back to All Messages
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    <!-- Message Content Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <span class="badge badge-{{ $contact->status }}" style="margin-bottom: 8px;">
                    {{ $contact->status }}
                </span>
                <h2 class="card-title">{{ $contact->subject ?? 'General Inquiry' }}</h2>
            </div>
            <div style="font-size: 13px; color: var(--text-muted);">
                Received {{ $contact->created_at->format('M d, Y \a\t h:i A') }}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 20px; margin-bottom: 20px;">
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Sender Name</div>
                <div style="font-size: 15px; font-weight: 700; color: var(--text-primary);">{{ $contact->name }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Email Address</div>
                <div style="font-size: 14px; color: var(--text-primary);">
                    <a href="mailto:{{ $contact->email }}" style="color: var(--accent-primary); text-decoration: none;">{{ $contact->email }}</a>
                </div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Phone Number</div>
                <div style="font-size: 14px; color: var(--text-primary);">{{ $contact->phone ?? 'Not provided' }}</div>
            </div>
        </div>

        <div>
            <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px;">Message Body</div>
            <div style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 20px; font-size: 14px; color: var(--text-primary); line-height: 1.7; white-space: pre-wrap;">{{ $contact->message }}</div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div>
        <div class="card">
            <h3 class="card-title" style="margin-bottom: 16px;">Message Actions</h3>

            <form method="POST" action="{{ route('admin.contacts.status', $contact) }}" style="margin-bottom: 20px;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $contact->status === 'unread' ? 'read' : 'unread' }}">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    {{ $contact->status === 'unread' ? 'Mark as Read' : 'Mark as Unread' }}
                </button>
            </form>

            <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject ?? 'RK Vibes Inquiry') }}" class="btn btn-secondary" style="width: 100%; margin-bottom: 20px;">
                Reply via Email
            </a>

            <div style="border-top: 1px solid var(--border-subtle); padding-top: 20px;">
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Permanently delete this message from {{ $contact->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
