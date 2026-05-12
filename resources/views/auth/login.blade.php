<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — PrinterInfo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1rem;
            padding: 2rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.375rem;
        }
        .form-input {
            width: 100%;
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            color: #e2e8f0;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .form-input::placeholder { color: #475569; }
        .btn-login {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease;
        }
        .btn-login:hover { background: #1d4ed8; }
        .btn-login:active { transform: scale(0.99); }
        .error-msg {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            color: #fca5a5;
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        {{-- Logo & Title --}}
        <div class="flex items-center gap-3 mb-7">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2zm-8 0v2a1 1 0 001 1h4a1 1 0 001-1v-2H9z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-tight">PrinterInfo</h1>
                <p class="text-slate-400 text-sm">Admin Portal</p>
            </div>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-msg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    placeholder="admin@example.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="mb-5">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600">
                    <span class="text-sm text-slate-400">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn-login">
                Masuk sebagai Admin
            </button>
        </form>

        {{-- Back to public --}}
        <div class="mt-5 text-center">
            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-slate-400 transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke halaman publik
            </a>
        </div>
    </div>
</body>
</html>
