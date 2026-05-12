@extends('layouts.app')

@section('title', 'Aktivitas Perangkat')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Aktivitas Perangkat</span>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Aktivitas Perangkat</h1>
        <p class="text-sm text-slate-500 mt-0.5">Monitor kondisi printer berdasarkan data import.</p>
    </div>
    @if($totalAttention > 0)
        <span class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ $totalAttention }} perlu perhatian
        </span>
    @endif
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Perangkat</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Lokasi</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Toner (CMYK)</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Cetak</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Alert</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($printers as $printer)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $printer->needs_attention ? 'bg-red-50/30' : '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                @if($printer->needs_attention)
                                    <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $printer->name }}</p>
                                    <p class="text-xs text-slate-400 font-mono">{{ $printer->serial_number ?? '–' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">{{ $printer->location ?? '–' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @foreach([
                                    ['level' => $printer->cyan_level,    'color' => '#00bcd4', 'label' => 'C'],
                                    ['level' => $printer->magenta_level, 'color' => '#e91e63', 'label' => 'M'],
                                    ['level' => $printer->yellow_level,  'color' => '#ffc107', 'label' => 'Y'],
                                    ['level' => $printer->black_level,   'color' => '#607d8b', 'label' => 'K'],
                                ] as $t)
                                    @if($t['level'] !== null)
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-mono font-bold {{ $t['level'] < 20 ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $t['level'] }}%
                                            </span>
                                            <span class="text-xs" style="color: {{ $t['color'] }}">{{ $t['label'] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-700">{{ number_format($printer->total_pages ?? 0) }}</td>
                        <td class="px-4 py-3">
                            @if(count($printer->alerts) > 0)
                                <div class="space-y-1">
                                    @foreach($printer->alerts as $alert)
                                        <span class="flex items-center gap-1 text-xs {{ $alert['type'] === 'offline' ? 'text-red-600' : 'text-amber-600' }}">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $alert['message'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-emerald-600 font-medium">✓ Normal</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusMap = ['online'=>'badge-online','offline'=>'badge-offline','idle'=>'badge-idle','warning'=>'badge-warning','error'=>'badge-offline'];
                            @endphp
                            <span class="badge {{ $statusMap[$printer->status ?? 'offline'] ?? 'badge-idle' }}">
                                {{ ucfirst($printer->status ?? 'offline') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                            Belum ada data perangkat. Import data printer terlebih dahulu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection