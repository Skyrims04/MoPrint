<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Models\Building;
use App\Models\BuildingTonerStock;
use App\Models\ImportHistory;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $printers = Printer::all();

        // Deteksi printer yang perlu perhatian (toner kritis ATAU offline)
        $attentionDevices = $printers->filter(function ($printer) {
            if ($printer->status === 'offline' || $printer->status === 'error' || $printer->status === 'warning') {
                return true;
            }
            foreach (['cyan_level','magenta_level','yellow_level','black_level'] as $col) {
                if ($printer->$col !== null && $printer->$col < 20) return true;
            }
            return false;
        })->map(function ($printer) {
            $alerts = [];
            if (in_array($printer->status, ['offline','error','warning'])) {
                $alerts[] = $printer->status === 'offline' ? 'Printer tidak aktif' : ucfirst($printer->status);
            }
            foreach ([
                'Cyan'    => $printer->cyan_level,
                'Magenta' => $printer->magenta_level,
                'Yellow'  => $printer->yellow_level,
                'Black'   => $printer->black_level,
            ] as $color => $level) {
                if ($level !== null && $level < 20) {
                    $alerts[] = "Toner {$color} kritis ({$level}%)";
                }
            }
            $printer->alert_messages = $alerts;
            return $printer;
        })->values()->take(6);

        $stats = [
            'total_printers'   => $printers->count(),
            'online_printers'  => $printers->where('status', 'online')->count(),
            'offline_printers' => $printers->where('status', 'offline')->count(),
            'needs_attention'  => $attentionDevices->count(),
            'critical_toner'   => BuildingTonerStock::whereColumn('stock_qty', '<', 'threshold')->count(),
            'total_pages'      => $printers->sum('total_pages'),
        ];

        $buildings     = Building::with('tonerStocks')->orderBy('code')->get();
        $importHistory = ImportHistory::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'printers', 'attentionDevices', 'buildings', 'importHistory'
        ));
    }
}