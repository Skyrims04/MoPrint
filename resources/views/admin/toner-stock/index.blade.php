@extends('layouts.app')

@section('title', 'Stok Toner')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Stok Toner</span>
@endsection

@push('styles')
<style>
    /* Light theme card — sesuai tema aplikasi */
    .toner-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.07);
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .toner-card:hover { box-shadow: 0 4px 12px 0 rgb(0 0 0 / 0.10); }
    .toner-card.has-low { border-color: #f59e0b; border-left: 3px solid #f59e0b; }

    .toner-row-bar {
        height: 6px;
        border-radius: 9999px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 5px;
    }
    .toner-row-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.6s ease;
    }

    .badge-low {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde68a;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-update {
        width: 100%;
        margin-top: 1rem;
        padding: 0.625rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #1e40af;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .btn-update:hover { background: #dbeafe; border-color: #93c5fd; }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.show { display: flex; }

    .modal-box {
        background: white;
        border-radius: 1rem;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.75rem;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);
    }

    .modal-input {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        transition: border-color 0.15s;
        text-align: center;
        font-family: monospace;
    }
    .modal-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

    .building-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    @media (max-width: 960px) { .building-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 600px) { .building-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Toner Stock Management</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola stok toner cadangan fisik per gedung.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@php
    $cmykColors = [
        'Cyan'    => '#00bcd4',
        'Magenta' => '#e91e63',
        'Yellow'  => '#ffc107',
        'Black'   => '#607d8b',
    ];
    $cmykOrder = ['Cyan', 'Magenta', 'Yellow', 'Black'];
@endphp

@if($buildings->isEmpty())
    <div class="card p-12 text-center">
        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
        </svg>
        <p class="text-slate-400 mb-2">Belum ada data gedung.</p>
        <p class="text-xs text-slate-400 font-mono mt-1">php artisan db:seed --class=BuildingSeeder</p>
    </div>
@else
    <div class="building-grid">
        @foreach($buildings as $building)
            @php
                $tonerMap = $building->tonerStocks->keyBy('type');
                $lowCount = $building->tonerStocks->filter(fn($t) => $t->stock_qty < $t->threshold)->count();

                $modalData = [];
                foreach ($cmykOrder as $type) {
                    $t = $tonerMap->get($type);
                    $modalData[] = [
                        'type'      => $type,
                        'color_hex' => $t?->color_hex ?? $cmykColors[$type],
                        'stock_qty' => $t?->stock_qty ?? 0,
                        'threshold' => $t?->threshold ?? 5,
                    ];
                }
            @endphp

            <div class="toner-card {{ $lowCount > 0 ? 'has-low' : '' }}">

                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $building->name }}</p>
                            @if($building->location)
                                <p class="text-xs text-slate-400">{{ $building->location }}</p>
                            @endif
                        </div>
                    </div>
                    @if($lowCount > 0)
                        <span class="badge-low">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $lowCount }} Low
                        </span>
                    @endif
                </div>

                {{-- CMYK Rows --}}
                <div class="space-y-3 flex-1">
                    @foreach($cmykOrder as $type)
                        @php
                            $toner     = $tonerMap->get($type);
                            $stockQty  = $toner?->stock_qty ?? 0;
                            $threshold = $toner?->threshold ?? 5;
                            $isLow     = $stockQty < $threshold;
                            $colorHex  = $toner?->color_hex ?? $cmykColors[$type];
                            $barColor  = $isLow ? '#ef4444' : $colorHex;
                            $maxStock  = max($stockQty, 30);
                            $barPct    = $stockQty > 0 ? min(100, round(($stockQty / $maxStock) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                         style="background-color: {{ $colorHex }}"></div>
                                    <span class="text-sm font-medium text-slate-700">{{ $type }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold font-mono text-sm {{ $isLow ? 'text-red-600' : 'text-slate-800' }}">
                                        {{ $stockQty }}
                                    </span>
                                    @if($isLow)
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="toner-row-bar">
                                <div class="toner-row-fill" style="width:{{ $barPct }}%; background-color:{{ $barColor }}"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Threshold: {{ $threshold }} units</p>
                        </div>
                    @endforeach
                </div>

                {{-- Update Stock Button --}}
                <button class="btn-update"
                        onclick="openModal({{ $building->id }}, '{{ $building->name }}', {{ json_encode($modalData) }})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Update Stock
                </button>
            </div>
        @endforeach
    </div>
@endif

{{-- Modal --}}
<div class="modal-overlay" id="updateModal" onclick="if(event.target===this) closeModal()">
    <div class="modal-box">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-800" id="modalTitle">Update Toner Stock</h2>
                <p class="text-sm text-slate-500 mt-0.5">Atur jumlah stok dan threshold per warna</p>
            </div>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="updateForm" method="POST">
            @csrf
            @method('PUT')
            <div id="modalContent" class="space-y-4"></div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit"
                        class="flex items-center gap-1.5 px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(buildingId, buildingName, toners) {
        document.getElementById('modalTitle').textContent = 'Update Toner Stock — ' + buildingName;
        document.getElementById('updateForm').action = `/admin/toner-stock/building/${buildingId}`;

        const content = document.getElementById('modalContent');
        content.innerHTML = '';

        toners.forEach((toner, idx) => {
            const hex = toner.color_hex || '#64748b';
            content.innerHTML += `
                <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-3.5 h-3.5 rounded-full flex-shrink-0" style="background-color: ${hex}"></div>
                        <span class="font-semibold text-slate-800 text-sm">${toner.type} Toner</span>
                    </div>
                    <input type="hidden" name="toners[${idx}][type]" value="${toner.type}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-2">Jumlah Stok (unit)</label>
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white">
                                <button type="button" onclick="changeVal('qty_${toner.type}', -1)"
                                        class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold border-r border-slate-200">−</button>
                                <input type="number" id="qty_${toner.type}"
                                       name="toners[${idx}][stock_qty]"
                                       value="${toner.stock_qty}" min="0"
                                       class="modal-input border-0 rounded-none flex-1">
                                <button type="button" onclick="changeVal('qty_${toner.type}', 1)"
                                        class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold border-l border-slate-200">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-2">Threshold Minimum</label>
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white">
                                <button type="button" onclick="changeVal('thr_${toner.type}', -1)"
                                        class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold border-r border-slate-200">−</button>
                                <input type="number" id="thr_${toner.type}"
                                       name="toners[${idx}][threshold]"
                                       value="${toner.threshold}" min="0"
                                       class="modal-input border-0 rounded-none flex-1">
                                <button type="button" onclick="changeVal('thr_${toner.type}', 1)"
                                        class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold border-l border-slate-200">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('updateModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function changeVal(inputId, delta) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.value = Math.max(0, parseInt(input.value || 0) + delta);
    }

    function closeModal() {
        document.getElementById('updateModal').classList.remove('show');
        document.body.style.overflow = '';
    }
</script>
@endpush