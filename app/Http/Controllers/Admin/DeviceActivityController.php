<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Printer;

class DeviceActivityController extends Controller
{
    public function index()
    {
        $printers = Printer::all()->map(function ($printer) {
            // Hitung toner paling rendah
            $tonerLevels = collect([
                'Cyan'    => $printer->cyan_level,
                'Magenta' => $printer->magenta_level,
                'Yellow'  => $printer->yellow_level,
                'Black'   => $printer->black_level,
            ])->filter(fn($v) => $v !== null);

            $lowestToner     = $tonerLevels->min();
            $lowestTonerName = $tonerLevels->filter(fn($v) => $v === $lowestToner)->keys()->first();

            // Tentukan alerts
            $alerts = [];

            if ($printer->status === 'offline') {
                $alerts[] = ['type' => 'offline', 'message' => 'Printer tidak aktif'];
            }

            foreach ([
                'Cyan'    => $printer->cyan_level,
                'Magenta' => $printer->magenta_level,
                'Yellow'  => $printer->yellow_level,
                'Black'   => $printer->black_level,
            ] as $color => $level) {
                if ($level !== null && $level < 20) {
                    $alerts[] = [
                        'type'    => 'toner',
                        'message' => "Toner {$color} kritis ({$level}%)",
                        'color'   => $color,
                        'level'   => $level,
                    ];
                }
            }

            $printer->alerts       = $alerts;
            $printer->needs_attention = count($alerts) > 0;
            $printer->lowest_toner = $lowestToner;

            return $printer;
        });

        // Sort: yang punya masalah duluan
        $printers = $printers->sortByDesc('needs_attention')->values();

        $totalAttention = $printers->where('needs_attention', true)->count();

        return view('admin.device-activity.index', compact('printers', 'totalAttention'));
    }
}