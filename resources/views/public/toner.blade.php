@extends('layouts.public')

@section('title', 'Stok Toner')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Stok Toner</h1>
    <p class="text-sm text-slate-500 mt-0.5">Pantau ketersediaan toner cadangan di semua gedung.</p>
</div>

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
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <p class="text-slate-400">Belum ada data stok toner</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($buildings as $building)
            @php
                $tonerMap = $building->tonerStocks->keyBy('type');
                $lowCount = $building->tonerStocks->filter(fn($t) => $t->stock_qty < $t->threshold)->count();
            @endphp

            <div class="card p-4">
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $building->name }}</p>
                            @if($building->location)
                                <p class="text-xs text-slate-400">{{ $building->location }}</p>
                            @endif
                        </div>
                    </div>
                    @if($lowCount > 0)
                        <span class="badge badge-warning">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $lowCount }} Low
                        </span>
                    @endif
                </div>

                {{-- Toner Rows --}}
                <div class="space-y-3">
                    @foreach($cmykOrder as $type)
                        @php
                            $toner     = $tonerMap->get($type);
                            $stockQty  = $toner?->stock_qty ?? 0;
                            $threshold = $toner?->threshold ?? 5;
                            $isLow     = $stockQty < $threshold;
                            $maxStock  = max($stockQty, 30);
                            $barPct    = $stockQty > 0 ? min(100, round(($stockQty / $maxStock) * 100)) : 0;
                            $colorHex  = $toner?->color_hex ?? $cmykColors[$type];
                            $barColor  = $isLow ? '#ef4444' : $colorHex;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                         style="background-color: {{ $colorHex }}"></div>
                                    <span class="text-sm font-medium text-slate-700">{{ $type }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-sm font-bold font-mono {{ $isLow ? 'text-red-600' : 'text-slate-800' }}">
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
                            <div class="toner-bar">
                                <div class="toner-fill" style="width: {{ $barPct }}%; background-color: {{ $barColor }}"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Threshold: {{ $threshold }} units</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection