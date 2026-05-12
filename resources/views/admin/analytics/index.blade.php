@extends('layouts.app')

@section('title', 'Analytics')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Analytics</span>
@endsection

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800">Analytics & Perbandingan</h1>
    <p class="text-sm text-slate-500 mt-0.5">Bandingkan data printer dari 2 periode import untuk analisa penggunaan dan optimasi biaya sewa.</p>
</div>

@if($allImports->isEmpty())
    <div class="card p-16 text-center">
        <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h3 class="text-lg font-semibold text-slate-600 mb-2">Belum ada data untuk dianalisis</h3>
        <p class="text-slate-400 text-sm mb-5">Import data printer minimal 2 kali agar bisa dibandingkan.</p>
        <a href="{{ route('admin.import-history.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-lg transition-colors">
            Import Data Sekarang →
        </a>
    </div>
@else

{{-- ── Pilih Periode ── --}}
<div class="card p-5 mb-6">
    <h2 class="text-sm font-bold text-slate-700 mb-4">Pilih 2 Periode untuk Dibandingkan</h2>
    <form method="GET" action="{{ route('admin.analytics') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Periode A</label>
            <select name="period_a" required
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih import --</option>
                @foreach($allImports as $imp)
                    <option value="{{ $imp['id'] }}" {{ $compareA == $imp['id'] ? 'selected' : '' }}>
                        {{ $imp['label'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Periode B</label>
            <select name="period_b" required
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih import --</option>
                @foreach($allImports as $imp)
                    <option value="{{ $imp['id'] }}" {{ $compareB == $imp['id'] ? 'selected' : '' }}>
                        {{ $imp['label'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Tampilkan Perbandingan
            </button>
        </div>
    </form>
</div>

@if($chartData)

{{-- ── Charts ── --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">

    {{-- Bar Chart: Total Prints --}}
    <div class="card p-5">
        <div class="mb-4">
            <h2 class="text-sm font-bold text-slate-800">Total Cetak per Printer</h2>
            <p class="text-xs text-slate-400 mt-0.5">Perbandingan jumlah halaman antar periode</p>
        </div>
        <div style="position: relative; height: 300px;">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    {{-- Bar Chart: Perubahan (diff) --}}
    <div class="card p-5">
        <div class="mb-4">
            <h2 class="text-sm font-bold text-slate-800">Selisih Penggunaan</h2>
            <p class="text-xs text-slate-400 mt-0.5">Perubahan total cetak: Periode B − Periode A</p>
        </div>
        <div style="position: relative; height: 300px;">
            <canvas id="diffChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Toner Comparison Table ── --}}
<div class="card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="text-sm font-bold text-slate-800">Detail Perbandingan per Printer</h2>
        <p class="text-xs text-slate-400 mt-0.5">Total cetak dan level toner CMYK dari kedua periode</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Printer</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Lokasi</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wide" colspan="2">Periode A</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-emerald-500 uppercase tracking-wide" colspan="2">Periode B</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Selisih</th>
                </tr>
                <tr class="border-b border-slate-100 bg-slate-50/30 text-xs text-slate-400">
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2 text-center font-medium">Total Cetak</th>
                    <th class="px-4 py-2 text-center font-medium">Toner CMYK</th>
                    <th class="px-4 py-2 text-center font-medium">Total Cetak</th>
                    <th class="px-4 py-2 text-center font-medium">Toner CMYK</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($chartData['comparison'] as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $row['name'] }}</td>
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $row['location'] }}</td>

                        {{-- Periode A --}}
                        <td class="px-4 py-3 text-center">
                            <span class="font-mono font-semibold text-blue-700">
                                {{ number_format($row['a']['total_pages']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @include('admin.analytics.partials.toner-mini', ['data' => $row['a']])
                        </td>

                        {{-- Periode B --}}
                        <td class="px-4 py-3 text-center">
                            <span class="font-mono font-semibold text-emerald-700">
                                {{ number_format($row['b']['total_pages']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @include('admin.analytics.partials.toner-mini', ['data' => $row['b']])
                        </td>

                        {{-- Selisih --}}
                        <td class="px-4 py-3">
                            @if($row['pages_diff'] > 0)
                                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600">
                                    ↑ +{{ number_format($row['pages_diff']) }}
                                </span>
                            @elseif($row['pages_diff'] < 0)
                                <span class="flex items-center gap-1 text-xs font-bold text-red-500">
                                    ↓ {{ number_format($row['pages_diff']) }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">— Sama</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── Rekomendasi ── --}}
@php
    $lowUsage = collect($chartData['comparison'])->filter(fn($p) => $p['b']['total_pages'] < 5000 && $p['b']['total_pages'] > 0)->values();
    $highUsage = collect($chartData['comparison'])->filter(fn($p) => $p['b']['total_pages'] >= 50000)->values();
@endphp

@if($lowUsage->count() > 0 || $highUsage->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if($lowUsage->count() > 0)
    <div class="card p-5 border-l-4 border-amber-400">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-amber-800">💡 Pertimbangkan Kurangi</h3>
                <p class="text-xs text-amber-600 mt-1">Printer dengan total cetak rendah (&lt;5.000 hal):</p>
                <ul class="mt-2 space-y-1">
                    @foreach($lowUsage as $p)
                        <li class="text-xs text-amber-700">
                            <span class="font-semibold">{{ $p['name'] }}</span>
                            <span class="text-amber-500"> — {{ $p['location'] }} ({{ number_format($p['b']['total_pages']) }} hal)</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if($highUsage->count() > 0)
    <div class="card p-5 border-l-4 border-emerald-400">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-emerald-800">✅ Pertahankan</h3>
                <p class="text-xs text-emerald-600 mt-1">Printer dengan utilisasi tinggi (≥50.000 hal):</p>
                <ul class="mt-2 space-y-1">
                    @foreach($highUsage as $p)
                        <li class="text-xs text-emerald-700">
                            <span class="font-semibold">{{ $p['name'] }}</span>
                            <span class="text-emerald-500"> — {{ $p['location'] }} ({{ number_format($p['b']['total_pages']) }} hal)</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

@else
{{-- Belum pilih periode --}}
<div class="card p-10 text-center border-2 border-dashed border-slate-200">
    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    <p class="text-slate-500 text-sm font-medium">Pilih 2 periode di atas untuk menampilkan perbandingan</p>
    <p class="text-slate-400 text-xs mt-1">Chart dan tabel perbandingan akan muncul di sini</p>
</div>
@endif

@endif

@endsection

@if($chartData)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($chartData['labels']);
const dataA  = @json($chartData['dataA']);
const dataB  = @json($chartData['dataB']);
const labelA = @json($chartData['labelA']);
const labelB = @json($chartData['labelB']);

// ── Bar Chart ──────────────────────────────────────────────────
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Periode A: ' + labelA,
                data: dataA,
                backgroundColor: 'rgba(59,130,246,0.75)',
                borderRadius: 4,
            },
            {
                label: 'Periode B: ' + labelB,
                data: dataB,
                backgroundColor: 'rgba(16,185,129,0.75)',
                borderRadius: 4,
            },
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } },
            tooltip: { callbacks: { label: ctx => ` ${ctx.dataset.label.split(':')[0]}: ${ctx.parsed.y.toLocaleString()} hal` } }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 30 } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v } }
        }
    }
});

// ── Diff Chart ─────────────────────────────────────────────────
const diffs  = dataB.map((b, i) => b - dataA[i]);
const colors = diffs.map(d => d >= 0 ? 'rgba(16,185,129,0.75)' : 'rgba(239,68,68,0.75)');

new Chart(document.getElementById('diffChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Selisih (B − A)',
            data: diffs,
            backgroundColor: colors,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` Selisih: ${ctx.parsed.y >= 0 ? '+' : ''}${ctx.parsed.y.toLocaleString()} hal` } }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 30 } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v } }
        }
    }
});
</script>
@endpush
@endif