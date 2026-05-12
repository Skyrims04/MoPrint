@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <span class="font-medium text-slate-700">Dashboard</span>
@endsection

@section('content')

{{-- Page Title + Action Buttons --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Admin Dashboard</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola printer, stok toner, dan data perangkat.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.import-history.index') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-sm font-medium text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Import
        </a>
    </div>
</div>

{{-- ─── STAT CARDS ─── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Printer</p>
                <p class="text-3xl font-bold text-slate-800 mt-1 leading-none">{{ $stats['total_printers'] ?? 0 }}</p>
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

    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Toner Kritis</p>
                <p class="text-3xl font-bold text-red-600 mt-1 leading-none">{{ $stats['critical_toner'] ?? 0 }}</p>
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

    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Perlu Perhatian</p>
                <p class="text-3xl font-bold text-amber-600 mt-1 leading-none">{{ $stats['needs_attention'] ?? 0 }}</p>
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

    <div class="card p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Cetak</p>
                <p class="text-3xl font-bold text-slate-800 mt-1 leading-none">{{ number_format($stats['total_pages'] ?? 0) }}</p>
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

{{-- ─── PRINTER + DEVICE ATTENTION ─── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">

    {{-- Printer Cards --}}
    <div class="xl:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Status Printer</h2>
            <a href="{{ route('admin.printers.index') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Kelola Printer
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($printers ?? [] as $printer)
                @include('dashboard.partials.printer-card', ['printer' => $printer])
            @empty
                <div class="sm:col-span-2 card p-8 text-center">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-slate-400">Belum ada data printer</p>
                    <a href="{{ route('admin.import-history.index') }}"
                       class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline">
                        + Import Data Printer
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Device Attention --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Perlu Perhatian</h2>
            @if(isset($attentionDevices) && $attentionDevices->count() > 0)
                <span class="badge badge-warning">{{ $attentionDevices->count() }} perangkat</span>
            @endif
        </div>

        <div class="card divide-y divide-slate-100">
            @forelse($attentionDevices ?? [] as $device)
                <div class="p-3.5 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $device->status === 'error' ? 'bg-red-50' : 'bg-amber-50' }}">
                        <svg class="w-4 h-4 {{ $device->status === 'error' ? 'text-red-500' : 'text-amber-500' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $device->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $device->status }}</p>
                        <p class="text-xs text-slate-400 mt-1 font-mono">{{ $device->location }}</p>
                    </div>
                    <a href="{{ route('admin.device-activity.edit', $device->id) }}"
                       class="text-xs text-blue-600 hover:text-blue-700 flex-shrink-0">Edit</a>
                </div>
            @empty
                <div class="p-6 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-500">Semua perangkat normal</p>
                    <p class="text-xs text-slate-400 mt-0.5">Tidak ada masalah saat ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ─── TONER + IMPORT HISTORY ─── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    {{-- Toner Stock per Gedung --}}
    <div class="xl:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Stok Toner per Gedung</h2>
            <a href="{{ route('admin.toner-stock.index') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Kelola Stok
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
                        @forelse($buildings ?? [] as $building)
                            @php
                                $tonerMap = $building->tonerStocks->keyBy('type');
                                $lowCount = $building->tonerStocks->filter(fn($t) => $t->stock_qty < $t->threshold)->count();
                                $cmykColors = ['Cyan'=>'#00bcd4','Magenta'=>'#e91e63','Yellow'=>'#ffc107','Black'=>'#607d8b'];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-slate-800">{{ $building->name }}</span>
                                </td>
                                @foreach(['Cyan','Magenta','Yellow','Black'] as $type)
                                    @php
                                        $t = $tonerMap->get($type);
                                        $qty = $t?->stock_qty ?? 0;
                                        $thr = $t?->threshold ?? 5;
                                        $isLow = $qty < $thr;
                                    @endphp
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2 h-2 rounded-full flex-shrink-0"
                                                 style="background-color: {{ $cmykColors[$type] }}"></div>
                                            <span class="font-mono text-sm font-semibold {{ $isLow ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $qty }}
                                            </span>
                                            @if($isLow)
                                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                            @endif
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

    {{-- Import History --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Riwayat Import</h2>
            <a href="{{ route('admin.import-history.index') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="card divide-y divide-slate-100">
            @forelse($importHistory ?? [] as $import)
                <div class="p-3.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $import->file_name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $import->imported_rows }} baris</p>
                        </div>
                        @if($import->status === 'success')
                            <span class="badge badge-online flex-shrink-0">Sukses</span>
                        @elseif($import->status === 'failed')
                            <span class="badge badge-offline flex-shrink-0">Gagal</span>
                        @else
                            <span class="badge badge-idle flex-shrink-0">Proses</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-1.5">{{ $import->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="p-6 text-center">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-xs text-slate-400">Belum ada riwayat import</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection