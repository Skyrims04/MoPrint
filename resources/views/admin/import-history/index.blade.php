@extends('layouts.app')

@section('title', 'Riwayat Import')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
    <span class="mx-1">/</span>
    <span class="font-medium text-slate-700">Riwayat Import</span>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Riwayat Import</h1>
        <p class="text-sm text-slate-500 mt-0.5">Import data printer dari file CSV dan lihat riwayatnya.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.import-template') }}"
           class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 bg-white text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download Template
        </a>
        <button onclick="document.getElementById('importModal').classList.add('show')"
                class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Import CSV
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('import_error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg flex items-start gap-2">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-semibold">Import Gagal</p>
            <p class="mt-0.5">{{ session('import_error') }}</p>
        </div>
    </div>
@endif

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">File</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Baris</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($importHistories as $import)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-800">{{ $import->file_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-mono font-semibold text-slate-700">{{ $import->imported_rows }}</td>
                        <td class="px-4 py-3">
                            @if($import->status === 'success')
                                <span class="badge badge-online">Sukses</span>
                            @elseif($import->status === 'failed')
                                <span class="badge badge-offline">Gagal</span>
                            @else
                                <span class="badge badge-idle">Proses</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400">
                            {{ $import->created_at->diffForHumans() }}
                            <span class="block font-mono text-slate-300">{{ $import->created_at->format('d M Y H:i') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.analytics.detail', $import->id) }}"
                               class="text-xs font-medium text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-slate-400">
                            Belum ada riwayat import.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($importHistories->hasPages())
    <div class="mt-4">{{ $importHistories->links() }}</div>
@endif

{{-- Import Modal --}}
<div class="modal-overlay" id="importModal" onclick="if(event.target===this) this.classList.remove('show')"
     style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;display:none;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:white;border-radius:1rem;width:100%;max-width:440px;padding:1.75rem;box-shadow:0 25px 50px -12px rgba(0,0,0,0.4);">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Import Data Printer</h2>
                <p class="text-sm text-slate-500 mt-0.5">Upload file CSV untuk import data</p>
            </div>
            <button onclick="document.getElementById('importModal').classList.remove('show')"
                    class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center mb-4">
                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <p class="text-sm text-slate-500 mb-3">Pilih file CSV untuk diimport</p>
                <input type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required
                       class="block w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="flex-1 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-lg transition-colors">
                    Import Sekarang
                </button>
                <button type="button"
                        onclick="document.getElementById('importModal').classList.remove('show')"
                        class="flex-1 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    #importModal.show { display: flex !important; }
</style>
@endpush

@push('scripts')
<script>
    // Buka modal otomatis kalau ada error validasi
    @if($errors->has('file'))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('importModal').classList.add('show');
        });
    @endif
</script>
@endpush

@endsection