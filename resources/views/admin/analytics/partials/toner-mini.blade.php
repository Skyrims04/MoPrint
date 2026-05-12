{{-- Partial: toner-mini.blade.php — tampilkan CMYK level kecil --}}
@php
    $cmyk = [
        ['label'=>'C', 'val'=>$data['cyan_level'],    'color'=>'#00bcd4'],
        ['label'=>'M', 'val'=>$data['magenta_level'], 'color'=>'#e91e63'],
        ['label'=>'Y', 'val'=>$data['yellow_level'],  'color'=>'#ffc107'],
        ['label'=>'K', 'val'=>$data['black_level'],   'color'=>'#607d8b'],
    ];
@endphp
<div class="flex items-center gap-1.5">
    @foreach($cmyk as $t)
        @if($t['val'] !== null)
            <div class="flex flex-col items-center">
                <span class="text-xs font-mono font-bold {{ $t['val'] < 20 ? 'text-red-600' : 'text-slate-700' }}">
                    {{ $t['val'] }}%
                </span>
                <div class="w-6 h-1.5 rounded-full" style="background: #e2e8f0; overflow:hidden;">
                    <div class="h-full rounded-full"
                         style="width:{{ $t['val'] }}%; background-color:{{ $t['val'] < 20 ? '#ef4444' : $t['color'] }}">
                    </div>
                </div>
                <span class="text-xs font-medium mt-0.5" style="color:{{ $t['color'] }}">{{ $t['label'] }}</span>
            </div>
        @else
            <div class="flex flex-col items-center opacity-30">
                <span class="text-xs font-mono text-slate-400">–</span>
                <div class="w-6 h-1.5 rounded-full bg-slate-200"></div>
                <span class="text-xs text-slate-400 mt-0.5">{{ $t['label'] }}</span>
            </div>
        @endif
    @endforeach
</div>