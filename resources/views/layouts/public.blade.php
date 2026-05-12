<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Printer Dashboard') — PrinterInfo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --header-height: 64px;
            --color-primary: #1e40af;
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

        .card {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-hover); }

        .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-online  { background: #dcfce7; color: #166534; }
        .badge-offline { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-idle    { background: #f1f5f9; color: #475569; }

        .toner-bar { height: 6px; border-radius: 9999px; background: #e2e8f0; overflow: hidden; }
        .toner-fill { height: 100%; border-radius: 9999px; transition: width 0.6s ease; }

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

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>

    @stack('styles')
</head>
<body class="h-full">

{{-- ── Public Navbar ── --}}
<header style="height: var(--header-height); background: #0f172a; position: sticky; top: 0; z-index: 40;">
    <div class="max-w-7xl mx-auto h-full flex items-center justify-between px-4 sm:px-6">
        {{-- Logo --}}
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2zm-8 0v2a1 1 0 001 1h4a1 1 0 001-1v-2H9z"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-semibold text-sm leading-tight">PrinterInfo</div>
                <div class="text-slate-500 text-xs">Monitoring Dashboard</div>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="hidden sm:flex items-center gap-1">
            <a href="{{ route('home') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('home') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/05' }}">
                Dashboard
            </a>
            <a href="{{ route('public.printers') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('public.printers') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/05' }}">
                Printer
            </a>
            <a href="{{ route('public.toner') }}"
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('public.toner') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/05' }}">
                Stok Toner
            </a>
        </nav>

        {{-- Admin Login Button --}}
        <a href="{{ route('admin.login') }}"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span class="hidden sm:inline">Admin</span>
        </a>
    </div>
</header>

{{-- Page Content --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 py-7">
    @yield('content')
</main>

<footer class="border-t border-slate-200 mt-8 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center text-xs text-slate-400">
        PrinterInfo Dashboard &copy; {{ date('Y') }} — Halaman ini dapat diakses oleh semua pengguna
    </div>
</footer>

@stack('scripts')
</body>
</html>