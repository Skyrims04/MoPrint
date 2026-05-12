<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Printer Dashboard') — PrinterInfo</title>

    {{-- Google Fonts: DM Sans + JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
            --color-primary: #1e40af;
            --color-primary-light: #dbeafe;
            --color-accent: #0ea5e9;
            --color-danger: #ef4444;
            --color-warning: #f59e0b;
            --color-success: #10b981;
            --color-surface: #f8fafc;
            --color-card: #ffffff;
            --color-border: #e2e8f0;
            --color-text: #0f172a;
            --color-muted: #64748b;
            --shadow-card: 0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.07);
            --shadow-hover: 0 4px 12px 0 rgb(0 0 0 / 0.10);
        }

        * { font-family: 'DM Sans', sans-serif; }
        code, .mono { font-family: 'JetBrains Mono', monospace; }
        body { background-color: var(--color-surface); color: var(--color-text); }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #0f172a;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }
        .sidebar-logo {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 1rem 0.75rem; }
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }
        .sidebar-nav-item:hover { background: rgba(255,255,255,0.06); color: #e2e8f0; }
        .sidebar-nav-item.active { background: var(--color-primary); color: #fff; }
        .sidebar-nav-item.active svg { opacity: 1; }
        .sidebar-nav-item svg { opacity: 0.6; width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar-nav-item.active svg { opacity: 1; }
        .sidebar-group-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #475569;
            padding: 0.875rem 0.875rem 0.375rem;
        }
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        /* Main layout */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .page-content { flex: 1; padding: 1.75rem 1.5rem; }

        /* Cards */
        .card {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-hover); }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-online  { background: #dcfce7; color: #166534; }
        .badge-offline { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-idle    { background: #f1f5f9; color: #475569; }

        /* Toner bar */
        .toner-bar {
            height: 6px;
            border-radius: 9999px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .toner-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.6s ease;
        }

        /* Dark printer card */
        .printer-card-dark {
            background: #1a1f2e;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.875rem;
            padding: 1.125rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .printer-card-dark:hover {
            border-color: rgba(96,165,250,0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Mobile sidebar toggle */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 49;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full">

{{-- Sidebar Overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2zm-8 0v2a1 1 0 001 1h4a1 1 0 001-1v-2H9z"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-700 text-sm leading-tight">PrinterInfo</div>
                <div class="text-slate-500 text-xs">Dashboard</div>
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="sidebar-group-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="sidebar-group-label mt-3">Manajemen</div>

        <a href="{{ route('admin.printers.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.printers.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2zm-8 0v2a1 1 0 001 1h4a1 1 0 001-1v-2H9z"/>
            </svg>
            Data Printer
        </a>

        <a href="{{ route('admin.toner-stock.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.toner-stock.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Stok Toner
        </a>

        <a href="{{ route('admin.device-activity.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.device-activity.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            Aktivitas Perangkat
        </a>

        <div class="sidebar-group-label mt-3">Riwayat</div>

        <a href="{{ route('admin.import-history.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.import-history.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Riwayat Import
        </a>

        <a href="{{ route('admin.analytics') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Analytics
        </a>
    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-600 text-slate-300">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-500 text-slate-200 truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-slate-300 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrapper">
    {{-- Top Header --}}
    <header class="top-header">
        <div class="flex items-center gap-3">
            {{-- Mobile menu button --}}
            <button class="lg:hidden p-1.5 rounded-md text-slate-500 hover:bg-slate-100" onclick="openSidebar()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 text-sm text-slate-500">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="flex items-center gap-3">
            {{-- Date --}}
            <span class="hidden sm:block text-xs text-slate-400 mono">
                {{ now()->translatedFormat('d M Y') }}
            </span>
            {{-- Notification bell --}}
            <button class="relative p-1.5 rounded-md text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(isset($alertCount) && $alertCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="page-content">
        @yield('content')
    </main>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
</script>

@stack('scripts')
</body>
</html>