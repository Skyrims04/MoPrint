@extends('layouts.app')

@section('title', 'Detail Import')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.import-history.index') }}" class="hover:text-slate-600">Riwayat Import</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Detail</span>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Detail Import</h1>
        <p class="text-sm text-slate-500 mt-0.5">
            {{ $import->created_at->format('d M Y H:i') }} — {{ $import->file_name }}
        </p>
    </div>
    <a href="{{ route('admin.import-history.index') }}"
       class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
        ← Kembali
    </a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Printer</p>
        <p class="text-3xl font-bold text-slate-800 mt-1">{{ $snapshots->count() }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Cetak</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($snapshots->sum('total_pages')) }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Toner Kritis</p>
        <p class="text-2xl font-bold text-red-600 mt-1">
            {{ $snapshots->filter(fn($s) =>
                ($s->cyan_level !== null && $s->cyan_level < 20) ||
                ($s->magenta_level !== null && $s->magenta_level < 20) ||
                ($s->yellow_level !== null && $s->yellow_level < 20) ||
                ($s->black_level !== null && $s->black_level < 20)
            )->count() }}
        </p>
        <p class="text-xs text-slate-400 mt-1">printer punya toner &lt;20%</p>
    </div>
    <div class="card p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status Import</p>
        <span class="badge {{ $import->status === 'success' ? 'badge-online' : 'badge-offline' }} mt-2 inline-flex">
            {{ ucfirst($import->status) }}
        </span>
    </div>
</div>

{{-- Printer Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Printer</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Lokasi</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Cetak</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Toner CMYK</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($snapshots as $snap)
                    @php
                        $statusMap = ['online'=>'badge-online','offline'=>'badge-offline','idle'=>'badge-idle','warning'=>'badge-warning','error'=>'badge-offline'];
                        $hasCritical = ($snap->cyan_level !== null && $snap->cyan_level < 20) ||
                                       ($snap->magenta_level !== null && $snap->magenta_level < 20) ||
                                       ($snap->yellow_level !== null && $snap->yellow_level < 20) ||
                                       ($snap->black_level !== null && $snap->black_level < 20);
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $hasCritical ? 'bg-red-50/20' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-800">{{ $snap->printer_name }}</p>
                            <p class="text-xs text-slate-400 font-mono">{{ $snap->serial_number ?? '–' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $snap->location ?? '–' }}</td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $statusMap[$snap->status] ?? 'badge-idle' }}">
                                {{ ucfirst($snap->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono font-semibold text-slate-800">{{ number_format($snap->total_pages) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @include('admin.analytics.partials.toner-mini', ['data' => [
                                'cyan_level'    => $snap->cyan_level,
                                'magenta_level' => $snap->magenta_level,
                                'yellow_level'  => $snap->yellow_level,
                                'black_level'   => $snap->black_level,
                            ]])
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 max-w-xs">{{ $snap->remarks ?? '–' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">Tidak ada data snapshot.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection