{{--
    Partial: dashboard/partials/printer-card.blade.php
    Fields: name, model, serial_number, location, status,
            total_pages, cyan_level, magenta_level,
            yellow_level, black_level, auto_email, remarks
--}}
@php
    $statusMap = [
        'online'  => ['label' => 'Online',     'bg' => '#dcfce7', 'color' => '#166534', 'dot' => '#22c55e'],
        'offline' => ['label' => 'Offline',    'bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444'],
        'idle'    => ['label' => 'Idle',       'bg' => '#f1f5f9', 'color' => '#475569', 'dot' => '#94a3b8'],
        'warning' => ['label' => 'Warning',    'bg' => '#fef9c3', 'color' => '#854d0e', 'dot' => '#f59e0b'],
        'error'   => ['label' => 'Error',      'bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444'],
    ];
    $s = $statusMap[$printer->status ?? 'offline'] ?? $statusMap['offline'];

    $toners = [
        ['label' => 'Cyan',    'value' => $printer->cyan_level,    'color' => '#00bcd4'],
        ['label' => 'Magenta', 'value' => $printer->magenta_level, 'color' => '#e91e63'],
        ['label' => 'Yellow',  'value' => $printer->yellow_level,  'color' => '#ffc107'],
        ['label' => 'Black',   'value' => $printer->black_level,   'color' => '#9ca3af'],
    ];
    $hasToner = collect($toners)->filter(fn($t) => $t['value'] !== null)->isNotEmpty();
@endphp

<div class="printer-card-dark">

    {{-- Header: Nama + Status badge --}}
    <div class="flex items-start justify-between gap-2 mb-1.5">
        <div class="flex items-center gap-2 min-w-0">
            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2zm-8 0v2a1 1 0 001 1h4a1 1 0 001-1v-2H9z"/>
            </svg>
            <h3 class="text-blue-400 font-bold text-sm leading-tight truncate">{{ $printer->name }}</h3>
        </div>
        {{-- Status badge --}}
        <span class="flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0"
              style="background: {{ $s['bg'] }}18; color: {{ $s['color'] }}; border: 1px solid {{ $s['dot'] }}44">
            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                  style="background: {{ $s['dot'] }}; {{ $printer->status === 'online' ? 'animation: pulse 2s infinite;' : '' }}"></span>
            {{ $s['label'] }}
        </span>
    </div>

    {{-- Model --}}
    <p class="text-slate-400 text-xs mb-3">{{ $printer->model ?? '–' }}</p>

    {{-- Info rows --}}
    <div class="space-y-1.5 mb-3">
        @if($printer->serial_number)
        <div class="flex items-center gap-1.5 text-xs">
            <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            <span class="text-slate-500">Serial:</span>
            <span class="text-slate-300 font-mono">{{ $printer->serial_number }}</span>
        </div>
        @endif

        @if($printer->location)
        <div class="flex items-center gap-1.5 text-xs">
            <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-slate-500">Location:</span>
            <span class="text-slate-300">{{ $printer->location }}</span>
        </div>
        @endif

        <div class="flex items-center gap-1.5 text-xs">
            <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-slate-500">Total Prints:</span>
            <span class="text-slate-300 font-mono">{{ number_format($printer->total_pages ?? 0) }}</span>
        </div>

        @if($printer->auto_email ?? false)
        <div class="flex items-center gap-1.5 text-xs">
            <svg class="w-3.5 h-3.5 text-violet-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="text-violet-300 text-xs font-semibold">Auto-Email ON</span>
        </div>
        @endif
    </div>

    {{-- Toner Levels --}}
    @if($hasToner)
    <div class="border-t border-white/10 pt-3 mb-3">
        <p class="text-blue-400 text-xs font-bold mb-2">Toner Levels</p>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
            @foreach($toners as $toner)
                @if($toner['value'] !== null)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-slate-300 text-xs">{{ $toner['label'] }}</span>
                        <span class="text-slate-300 text-xs font-mono font-semibold
                            {{ $toner['value'] < 20 ? 'text-red-400' : '' }}">
                            {{ $toner['value'] }}%
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.1)">
                        <div class="h-full rounded-full transition-all"
                             style="width: {{ $toner['value'] }}%;
                                    background-color: {{ $toner['value'] < 20 ? '#ef4444' : $toner['color'] }}">
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Remarks --}}
    @if($printer->remarks ?? null)
    <div class="border-t border-white/10 pt-3">
        <div class="flex items-start gap-1.5">
            <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <div>
                <span class="text-slate-400 text-xs font-semibold">Remarks:</span>
                <p class="text-slate-400 text-xs mt-0.5">{{ $printer->remarks }}</p>
            </div>
        </div>
    </div>
    @endif
</div>