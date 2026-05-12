<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    public function index()
    {
        $printers = Printer::orderBy('name')->paginate(20);
        return view('admin.printers.index', compact('printers'));
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();
        return redirect()->route('admin.printers.index')
            ->with('success', "Printer {$printer->name} berhasil dihapus.");
    }
}