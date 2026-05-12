@extends('layouts.public')

@section('title', 'Daftar Printer')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Printer</h1>
    <p class="text-sm text-slate-500 mt-0.5">Status semua printer yang terdaftar di sistem.</p>
</div>

@if($printers->isEmpty())
    <div class="card p-12 text-center">
        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M17 17H7a2 2 0 01-2-2V9a2 2 0 012-2h1V5a1 1 0 011-1h6a1 1 0 011 1v2h1a2 2 0 012 2v6a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-400">Belum ada data printer</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($printers as $printer)
            @include('dashboard.partials.printer-card', ['printer' => $printer])
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($printers->hasPages())
        <div class="mt-6">{{ $printers->links() }}</div>
    @endif
@endif

@endsection