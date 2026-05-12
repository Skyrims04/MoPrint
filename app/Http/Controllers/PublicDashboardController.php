<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Building;
use App\Models\BuildingTonerStock;

class PublicDashboardController extends Controller
{
    public function index()
    {
        $printers = Printer::all();

        $attentionDevices = $printers->filter(function ($printer) {
            if (in_array($printer->status, ['offline', 'error', 'warning'])) return true;
            foreach (['cyan_level','magenta_level','yellow_level','black_level'] as $col) {
                if ($printer->$col !== null && $printer->$col < 20) return true;
            }
            return false;
        })->take(6)->values();

        $stats = [
            'total_printers'   => $printers->count(),
            'online_printers'  => $printers->where('status', 'online')->count(),
            'offline_printers' => $printers->where('status', 'offline')->count(),
            'needs_attention'  => $attentionDevices->count(),
            'critical_toner'   => BuildingTonerStock::whereColumn('stock_qty', '<', 'threshold')->count(),
            'total_pages'      => $printers->sum('total_pages'),
        ];

        $buildings = Building::with('tonerStocks')->orderBy('code')->get();

        return view('dashboard.index', compact(
            'stats', 'printers', 'attentionDevices', 'buildings',
        ));
    }

    public function printers()
    {
        $printers = Printer::paginate(12);
        return view('public.printers', compact('printers'));
    }

    public function toner()
    {
        $buildings = Building::with('tonerStocks')->orderBy('code')->get();
        return view('public.toner', compact('buildings'));
    }
}