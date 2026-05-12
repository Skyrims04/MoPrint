<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingTonerStock;
use Illuminate\Http\Request;

class TonerStockController extends Controller
{
    public function index()
    {
        $buildings = Building::with(['tonerStocks' => function($q) {
            $q->orderByRaw("CASE type WHEN 'Cyan' THEN 1 WHEN 'Magenta' THEN 2 WHEN 'Yellow' THEN 3 WHEN 'Black' THEN 4 ELSE 5 END");
        }])->orderBy('code')->get();

        return view('admin.toner-stock.index', compact('buildings'));
    }

    public function updateBuilding(Request $request, Building $building)
    {
        $request->validate([
            'toners'             => 'required|array',
            'toners.*.type'      => 'required|in:Cyan,Magenta,Yellow,Black',
            'toners.*.stock_qty' => 'required|integer|min:0',
            'toners.*.threshold' => 'required|integer|min:0',
        ]);

        foreach ($request->toners as $tonerData) {
            BuildingTonerStock::updateOrCreate(
                ['building_id' => $building->id, 'type' => $tonerData['type']],
                [
                    'stock_qty' => $tonerData['stock_qty'],
                    'threshold' => $tonerData['threshold'],
                ]
            );
        }

        return redirect()->route('admin.toner-stock.index')
            ->with('success', "Stok toner {$building->name} berhasil diupdate.");
    }
}