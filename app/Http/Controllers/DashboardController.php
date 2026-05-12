<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\TonerStock;
use App\Models\ImportHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats Summary ─────────────────────────────────────
        $printers = Printer::with('toners')->get();

        $stats = [
            'total_printers'   => $printers->count(),
            'online_printers'  => $printers->where('status', 'online')->count(),
            'offline_printers' => $printers->where('status', 'offline')->count(),
            'needs_attention'  => $printers->whereIn('status', ['warning', 'error'])->count(),
            'critical_toner'   => TonerStock::where('level', '<', 20)->count(),
            'pages_today'      => $printers->sum('pages_today'),
        ];

        // ── Device Attention (warning & error) ────────────────
        $attentionDevices = Printer::whereIn('status', ['warning', 'error'])
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        // ── Toner Stock (sorted: critical first) ──────────────
        $tonerStocks = TonerStock::with('printer')
            ->orderBy('level', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($toner) {
                $toner->printer_name = $toner->printer->name ?? '–';
                return $toner;
            });

        // ── Import History (latest 5) ─────────────────────────
        $importHistory = ImportHistory::latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'stats',
            'printers',
            'attentionDevices',
            'tonerStocks',
            'importHistory',
        ));
    }
}
