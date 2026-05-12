@extends('layouts.app')

@section('title', 'Data Printer')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Data Printer</span>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Data Printer</h1>
        <p class="text-sm text-slate-500 mt-0.5">Data printer dikelola melalui import file Excel.</p>
    </div>
    <a href="{{ route('admin.import-history.index') }}"
       class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Import Data
    </a>
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
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Model</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Serial</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Lokasi</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">IP Address</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Cetak</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($printers as $printer)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-slate-800">{{ $printer->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $printer->model ?? '–' }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $printer->serial_number ?? '–' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $printer->location ?? '–' }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $printer->ip_address ?? '–' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusMap = [
                                    'online'  => 'badge-online',
                                    'offline' => 'badge-offline',
                                    'idle'    => 'badge-idle',
                                    'warning' => 'badge-warning',
                                    'error'   => 'badge-offline',
                                ];
                            @endphp
                            <span class="badge {{ $statusMap[$printer->status] ?? 'badge-idle' }}">
                                {{ ucfirst($printer->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-sm font-semibold text-slate-700">
                            {{ number_format($printer->total_pages ?? 0) }}
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.printers.destroy', $printer->id) }}"
                                  onsubmit="return confirm('Hapus printer {{ $printer->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-400 text-sm">Belum ada data printer.</p>
                            <a href="{{ route('admin.import-history.index') }}"
                               class="mt-2 inline-flex items-center gap-1.5 text-sm text-blue-600 hover:underline">
                                Import data printer sekarang →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($printers->hasPages())
    <div class="mt-4">{{ $printers->links() }}</div>
@endif

@endsection