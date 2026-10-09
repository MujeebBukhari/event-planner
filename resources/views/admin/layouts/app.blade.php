<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin Dashboard') — RK Vibes</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0f17;
            --bg-surface: #131b26;
            --bg-surface-elevated: #1a2433;
            --border-subtle: #202d3e;
            --border-focus: #375fac;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-primary: #017b45;
            --accent-primary-hover: #029d58;
            --accent-secondary: #375fac;
            --accent-cyan: #66cbe6;
            --accent-amber: #f59e0b;
            --accent-emerald: #017b45;
            --accent-rose: #f43f5e;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-surface);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .sidebar-brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #017b45, #375fac);
            color: #ffffff;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(1, 123, 69, 0.35);
        }

        .sidebar-brand-text h1 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.2px;
        }

        .sidebar-brand-text span {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent-cyan);
        }

        .sidebar-nav {
            padding: 20px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: 10px 12px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background-color: var(--bg-surface-elevated);
        }

        .nav-link.active {
            color: #ffffff;
            background-color: rgba(55, 95, 172, 0.22);
            border: 1px solid rgba(102, 203, 230, 0.4);
            font-weight: 600;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            opacity: 0.85;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border-subtle);
            background-color: rgba(0, 0, 0, 0.15);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #475569, #334155);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            border: 1px solid var(--border-subtle);
        }

        .user-meta {
            overflow: hidden;
            flex: 1;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--accent-emerald);
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .user-role::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--accent-emerald);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
            background-color: var(--bg-surface-elevated);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: rgba(244, 63, 94, 0.12);
            border-color: rgba(244, 63, 94, 0.3);
            color: var(--accent-rose);
        }

        /* Main Content Area */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-header {
            height: 64px;
            padding: 0 32px;
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .public-site-link {
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s ease-in-out;
        }

        .public-site-link:hover {
            color: var(--text-primary);
        }

        .admin-content {
            padding: 32px;
            flex: 1;
        }

        /* Flash Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .alert-error {
            background-color: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #fb7185;
        }

        /* Shared Admin UI Components */
        .card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            background-color: var(--bg-surface);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .admin-table th {
            background-color: var(--bg-surface-elevated);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-primary);
            vertical-align: middle;
        }

        .admin-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.12);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-confirmed, .badge-completed, .badge-active, .badge-read {
            background-color: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-cancelled, .badge-inactive, .badge-unread {
            background-color: rgba(244, 63, 94, 0.12);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease-in-out;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #017b45, #375fac);
            color: #ffffff;
            border-color: #375fac;
            box-shadow: 0 4px 12px rgba(1, 123, 69, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #029d58, #4875cc);
            border-color: #66cbe6;
            color: #ffffff;
        }

        .btn-secondary {
            background-color: var(--bg-surface-elevated);
            color: var(--text-secondary);
            border-color: var(--border-subtle);
        }

        .btn-secondary:hover {
            color: var(--text-primary);
            background-color: #263347;
        }

        .btn-danger {
            background-color: rgba(244, 63, 94, 0.12);
            color: #fb7185;
            border-color: rgba(244, 63, 94, 0.3);
        }

        .btn-danger:hover {
            background-color: rgba(244, 63, 94, 0.25);
            color: #ffffff;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 9px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
            background-color: var(--bg-surface);
            color: var(--text-primary);
            font-size: 13px;
            font-family: inherit;
            transition: border-color 0.15s ease-in-out;
        }

        .filter-group .form-input, .filter-group .form-select {
            width: auto;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-textarea {
            width: 100%;
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .form-error {
            color: #fb7185;
            font-size: 12px;
            margin-top: 5px;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .admin-content {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="RK Vibes" style="height: 38px; width: auto; object-fit: contain;">
                <div class="sidebar-brand-text">
                    <h1>RK Vibes</h1>
                    <span>Admin Portal</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Navigation</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9"></rect>
                        <rect x="14" y="3" width="7" height="5"></rect>
                        <rect x="14" y="12" width="7" height="9"></rect>
                        <rect x="3" y="16" width="7" height="5"></rect>
                    </svg>
                    Dashboard
                </a>

                <div class="nav-section-title">Management</div>
                <a href="{{ route('admin.event.edit') }}" class="nav-link {{ request()->routeIs('admin.event.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Featured Event
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        <path d="M13 5v2"></path>
                        <path d="M13 17v2"></path>
                        <path d="M13 11v2"></path>
                    </svg>
                    Bookings
                </a>

                <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Contact Messages
                </a>

                <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    Services
                </a>

                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Gallery
                </a>

                <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                    </svg>
                    Testimonials
                </a>
            </nav>

            <div class="sidebar-footer">
                @if(Auth::check())
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="user-meta">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <div class="header-title">
                    @yield('header_title', 'Dashboard Overview')
                </div>
                <div class="header-actions">
                    <a href="{{ route('home') }}" target="_blank" class="public-site-link">
                        <span>View Public Site</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
