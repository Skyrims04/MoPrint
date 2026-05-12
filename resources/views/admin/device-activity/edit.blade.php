@extends('layouts.app')

@section('title', 'Update Status Perangkat')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.device-activity.index') }}" class="hover:text-slate-600">Aktivitas Perangkat</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Update Status</span>
@endsection

@section('content')

<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Update Status Perangkat</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $printer->name }} — {{ $printer->location }}</p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.device-activity.update', $printer->id) }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['online' => 'Online', 'offline' => 'Offline', 'idle' => 'Idle', 'warning' => 'Warning', 'error' => 'Error'] as $val => $label)
                            <option value="{{ $val }}" {{ $printer->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Halaman Hari Ini</label>
                        <input type="number" name="pages_today" value="{{ old('pages_today', $printer->pages_today ?? 0) }}" min="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Total Halaman</label>
                        <input type="number" name="total_pages" value="{{ old('total_pages', $printer->total_pages ?? 0) }}" min="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Update
                </button>
                <a href="{{ route('admin.device-activity.index') }}"
                   class="px-5 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection