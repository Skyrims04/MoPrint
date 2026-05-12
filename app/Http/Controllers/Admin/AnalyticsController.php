<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrinterSnapshot;
use App\Models\ImportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Halaman utama analytics — pilih 2 periode untuk dibandingkan
     */
    public function index(Request $request)
    {
        // Semua import yang punya snapshot
        $allImports = ImportHistory::whereHas('snapshots')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($i) => [
                'id'    => $i->id,
                'label' => $i->created_at->format('d M Y H:i') . ' — ' . $i->file_name . ' (' . $i->imported_rows . ' printer)',
            ]);

        $compareA  = $request->input('period_a');
        $compareB  = $request->input('period_b');
        $chartData = null;

        if ($compareA && $compareB && $compareA !== $compareB) {
            $snapsA = PrinterSnapshot::where('import_history_id', $compareA)->get()->keyBy('printer_name');
            $snapsB = PrinterSnapshot::where('import_history_id', $compareB)->get()->keyBy('printer_name');

            $importA = ImportHistory::find($compareA);
            $importB = ImportHistory::find($compareB);

            // Gabungkan semua nama printer dari kedua periode
            $allNames = $snapsA->keys()->merge($snapsB->keys())->unique()->sort()->values()->toArray();

            $dataA = array_map(fn($n) => $snapsA[$n]->total_pages ?? 0, $allNames);
            $dataB = array_map(fn($n) => $snapsB[$n]->total_pages ?? 0, $allNames);

            // Toner comparison per printer
            $tonerComparison = [];
            foreach ($allNames as $name) {
                $sA = $snapsA[$name] ?? null;
                $sB = $snapsB[$name] ?? null;
                $tonerComparison[] = [
                    'name'     => $name,
                    'location' => $sA?->location ?? $sB?->location ?? '–',
                    'a' => [
                        'total_pages'   => $sA?->total_pages ?? 0,
                        'cyan_level'    => $sA?->cyan_level,
                        'magenta_level' => $sA?->magenta_level,
                        'yellow_level'  => $sA?->yellow_level,
                        'black_level'   => $sA?->black_level,
                    ],
                    'b' => [
                        'total_pages'   => $sB?->total_pages ?? 0,
                        'cyan_level'    => $sB?->cyan_level,
                        'magenta_level' => $sB?->magenta_level,
                        'yellow_level'  => $sB?->yellow_level,
                        'black_level'   => $sB?->black_level,
                    ],
                    'pages_diff' => ($sB?->total_pages ?? 0) - ($sA?->total_pages ?? 0),
                ];
            }

            $chartData = [
                'labels'      => $allNames,
                'labelA'      => $importA?->created_at->format('d M Y') . ' (' . $importA?->file_name . ')',
                'labelB'      => $importB?->created_at->format('d M Y') . ' (' . $importB?->file_name . ')',
                'dataA'       => $dataA,
                'dataB'       => $dataB,
                'comparison'  => $tonerComparison,
            ];
        }

        return view('admin.analytics.index', compact('allImports', 'compareA', 'compareB', 'chartData'));
    }

    /**
     * Detail snapshot satu import tertentu
     */
    public function detail($importId)
    {
        $import    = ImportHistory::findOrFail($importId);
        $snapshots = PrinterSnapshot::where('import_history_id', $importId)
            ->orderBy('printer_name')
            ->get();

        return view('admin.analytics.detail', compact('import', 'snapshots'));
    }
}