@extends('layouts.public')

@section('title', 'Dashboard')

@section('content')
{{-- =============================================
     DASHBOARD — Printer Information
     ============================================= --}}

{{-- Page Title --}}
<div class="mb-6">
    <h1 class="text-xl font-700 text-slate-800 tracking-tight">Printer Information Dashboard</h1>
    <p class="text-sm text-slate-500 mt-0.5">Pantau status printer, stok toner, dan aktivitas perangkat secara real-time.</p>
</div>

{{-- ─── STAT SUMMARY CARDS ────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total Printer --}}
    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-600 text-slate-500 uppercase tracking-wide">Total Printer</p>
                <p class="text-3xl font-700 text-slate-800 mt-1 leading-none">{{ $stats['total_printers'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs">
            <span class="badge badge-online">{{ $stats['online_printers'] ?? 0 }} Online</span>
            <span class="badge badge-offline">{{ $stats['offline_printers'] ?? 0 }} Offline</span>
        </div>
    </div>

    {{-- Toner Kritis --}}
    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-600 text-slate-500 uppercase tracking-wide">Toner Kritis</p>
                <p class="text-3xl font-700 text-red-600 mt-1 leading-none">{{ $stats['critical_toner'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-xs text-slate-500">Perlu segera diisi ulang</p>
    </div>

    {{-- Perlu Perhatian --}}
    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-600 text-slate-500 uppercase tracking-wide">Perlu Perhatian</p>
                <p class="text-3xl font-700 text-amber-600 mt-1 leading-none">{{ $stats['needs_attention'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-xs text-slate-500">Error / peringatan aktif</p>
    </div>

    {{-- Total Cetak --}}
    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-600 text-slate-500 uppercase tracking-wide">Total Cetak</p>
                <p class="text-3xl font-700 text-slate-800 mt-1 leading-none">{{ number_format($stats['total_pages'] ?? 0) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-xs text-slate-500">Akumulasi semua printer</p>
    </div>
</div>

{{-- ─── MAIN GRID: Printer Cards + Device Attention ─── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">

    {{-- ── Printer Cards (2/3 width) ── --}}
    <div class="xl:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-600 text-slate-700">Status Printer</h2>
            <a href="{{ route('public.printers') }}"
               class="text-xs font-500 text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($printers ?? [] as $printer)
                @include('dashboard.partials.printer-card', ['printer' => $printer])
            @empty
                <div class="sm:col-span-2 xl:col-span-3 card p-8 text-center">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-slate-400">Belum ada data printer</p>
                    <a href="{{ route('admin.login') }}"
                       class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline">
                        + Import Data Printer (Login Admin)
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ── Device Attention (1/3 width) ── --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-600 text-slate-700">Perlu Perhatian</h2>
            @if(isset($attentionDevices) && $attentionDevices->count() > 0)
                <span class="badge badge-warning">{{ $attentionDevices->count() }} perangkat</span>
            @endif
        </div>

        <div class="card divide-y divide-slate-100">
            @forelse($attentionDevices ?? [] as $device)
                @php
                    $isOffline = in_array($device->status, ['offline','error']);
                    $alerts = [];
                    if ($isOffline) $alerts[] = 'Printer tidak aktif';
                    foreach (['cyan_level'=>'Cyan','magenta_level'=>'Magenta','yellow_level'=>'Yellow','black_level'=>'Black'] as $col => $label) {
                        if ($device->$col !== null && $device->$col < 20) $alerts[] = "Toner {$label} kritis ({$device->$col}%)";
                    }
                @endphp
                <div class="p-3.5 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $isOffline ? 'bg-red-50' : 'bg-amber-50' }}">
                        <svg class="w-4 h-4 {{ $isOffline ? 'text-red-500' : 'text-amber-500' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-600 text-slate-800 truncate">{{ $device->name }}</p>
                        @foreach($alerts as $alert)
                            <p class="text-xs {{ $isOffline ? 'text-red-500' : 'text-amber-500' }} mt-0.5">{{ $alert }}</p>
                        @endforeach
                        <p class="text-xs text-slate-400 mono mt-1">{{ $device->location ?? '–' }}</p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs font-600 text-slate-500">Semua perangkat normal</p>
                    <p class="text-xs text-slate-400 mt-0.5">Tidak ada masalah saat ini</p>
                </div>
            @endforelse
        </div>
    </div>
{{-- ─── BOTTOM: Toner Stock per Gedung ─── --}}
<div class="mb-4">
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Stok Toner per Gedung</h2>
            <a href="{{ route('public.toner') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Gedung</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Cyan</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Magenta</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Yellow</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Black</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php $cmykColors = ['Cyan'=>'#00bcd4','Magenta'=>'#e91e63','Yellow'=>'#ffc107','Black'=>'#607d8b']; @endphp
                        @forelse($buildings ?? [] as $building)
                            @php
                                $tonerMap = $building->tonerStocks->keyBy('type');
                                $lowCount = $building->tonerStocks->filter(fn($t) => $t->stock_qty < $t->threshold)->count();
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $building->name }}</td>
                                @foreach(['Cyan','Magenta','Yellow','Black'] as $type)
                                    @php
                                        $t = $tonerMap->get($type);
                                        $qty = $t?->stock_qty ?? 0;
                                        $isLow = $qty < ($t?->threshold ?? 5);
                                    @endphp
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2 h-2 rounded-full flex-shrink-0"
                                                 style="background-color: {{ $cmykColors[$type] }}"></div>
                                            <span class="font-mono text-sm font-semibold {{ $isLow ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $qty }}
                                            </span>
                                        </div>
                                    </td>
                                @endforeach
                                <td class="px-4 py-3">
                                    @if($lowCount > 0)
                                        <span class="badge badge-warning">{{ $lowCount }} Low</span>
                                    @else
                                        <span class="badge badge-online">Normal</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">
                                    Belum ada data stok toner
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection