<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportHistory;
use App\Models\Printer;
use App\Models\PrinterSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportHistoryController extends Controller
{
    public function index()
    {
        $importHistories = ImportHistory::latest()->paginate(15);
        return view('admin.import-history.index', compact('importHistories'));
    }

    public function downloadTemplate()
    {
        $path = public_path('template/MoPrint_Import_Template.xlsx');
        if (file_exists($path)) {
            return response()->download($path, 'MoPrint_Import_Template.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }
        return back()->with('error', 'Template tidak ditemukan di server.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $file      = $request->file('file');
        $fileName  = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        // Validasi ekstensi manual
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
            return back()->withErrors([
                'file' => 'Format file tidak didukung. Gunakan file Excel (.xlsx, .xls) atau CSV (.csv).'
            ]);
        }
        $rows      = 0;
        $status    = 'success';
        $importedAt = now();

        try {
            DB::beginTransaction();

            // Buat record ImportHistory dulu untuk dapat ID-nya
            $importRecord = ImportHistory::create([
                'file_name'     => $fileName,
                'imported_rows' => 0,
                'status'        => 'processing',
            ]);

            $printerData = [];

            if (in_array($extension, ['xlsx', 'xls'])) {
                // PhpSpreadsheet v5 compatible
                $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet       = $spreadsheet->getActiveSheet();
                $data        = $sheet->toArray(null, true, true, true);

                // ── Validasi & deteksi posisi header ──────────────
                $expectedHeaders = [
                    'A' => 'Printer Name',
                    'B' => 'Model',
                    'C' => 'Serial Number',
                    'D' => 'Location',
                    'E' => 'Total Prints',
                    'F' => 'Status',
                    'G' => 'Auto Email',
                    'H' => 'Cyan Toner (%)',
                    'I' => 'Magenta Toner (%)',
                    'J' => 'Yellow Toner (%)',
                    'K' => 'Black Toner (%)',
                    'L' => 'Remarks',
                ];

                // Cari baris header secara otomatis (cek baris 1 s/d 5)
                $headerRowNum = null;
                foreach (range(1, 5) as $rowNum) {
                    $row = $data[$rowNum] ?? null;
                    if (!$row) continue;
                    $cellA = trim(preg_replace('/\s+/', ' ', str_replace('*', '', $row['A'] ?? '')));
                    if (strtolower($cellA) === 'printer name') {
                        $headerRowNum = $rowNum;
                        break;
                    }
                }

                // Kalau tidak ketemu header yang sesuai → tolak
                if (!$headerRowNum) {
                    DB::rollBack();
                    $importRecord->update(['status' => 'failed', 'imported_rows' => 0]);
                    return redirect()->route('admin.import-history.index')
                        ->with('import_error', 'Import gagal: File tidak sesuai template MoPrint. Kolom "Printer Name" tidak ditemukan. Gunakan template yang disediakan.');
                }

                // Validasi semua kolom header
                $headerRow      = $data[$headerRowNum];
                $invalidHeaders = [];
                foreach ($expectedHeaders as $col => $expected) {
                    $actual = trim(preg_replace('/\s+/', ' ', str_replace('*', '', $headerRow[$col] ?? '')));
                    if (strtolower($actual) !== strtolower($expected)) {
                        $invalidHeaders[] = "Kolom {$col}: diharapkan \"{$expected}\"";
                    }
                }

                if (!empty($invalidHeaders)) {
                    DB::rollBack();
                    $importRecord->update(['status' => 'failed', 'imported_rows' => 0]);
                    return redirect()->route('admin.import-history.index')
                        ->with('import_error', 'Import gagal: Format kolom tidak sesuai template. ' .
                            implode(', ', array_slice($invalidHeaders, 0, 3)) .
                            (count($invalidHeaders) > 3 ? ', dan ' . (count($invalidHeaders) - 3) . ' kolom lainnya.' : '.'));
                }
                // ── End validasi ───────────────────────────────────

                // Skip semua baris sampai setelah header
                $skipRows   = $headerRowNum;
                $currentRow = 0;
                foreach ($data as $row) {
                    $currentRow++;
                    if ($currentRow <= $skipRows) continue;

                    $name = trim($row['A'] ?? '');
                    if (empty($name)) continue;

                    $statusVal = strtolower(trim($row['F'] ?? 'online'));
                    if (!in_array($statusVal, ['online','offline','idle','warning','error'])) {
                        $statusVal = 'online';
                    }

                    $printerData[] = [
                        'name'          => $name,
                        'model'         => trim($row['B'] ?? ''),
                        'serial_number' => trim($row['C'] ?? ''),
                        'location'      => trim($row['D'] ?? ''),
                        'total_pages'   => (int)($row['E'] ?? 0),
                        'status'        => $statusVal,
                        'auto_email'    => strtolower(trim($row['G'] ?? '')) === 'enabled',
                        'cyan_level'    => is_numeric($row['H'] ?? null) ? (int)$row['H'] : null,
                        'magenta_level' => is_numeric($row['I'] ?? null) ? (int)$row['I'] : null,
                        'yellow_level'  => is_numeric($row['J'] ?? null) ? (int)$row['J'] : null,
                        'black_level'   => is_numeric($row['K'] ?? null) ? (int)$row['K'] : null,
                        'remarks'       => trim($row['L'] ?? ''),
                    ];
                }
            } else {
                $handle  = fopen($file->getRealPath(), 'r');
                $isFirst = true;
                while (($row = fgetcsv($handle)) !== false) {
                    if ($isFirst) { $isFirst = false; continue; }
                    $name = trim($row[0] ?? '');
                    if (empty($name)) continue;

                    $statusVal = strtolower(trim($row[5] ?? 'online'));
                    if (!in_array($statusVal, ['online','offline','idle','warning','error'])) {
                        $statusVal = 'online';
                    }

                    $printerData[] = [
                        'name'          => $name,
                        'model'         => trim($row[1] ?? ''),
                        'serial_number' => trim($row[2] ?? ''),
                        'location'      => trim($row[3] ?? ''),
                        'total_pages'   => (int)($row[4] ?? 0),
                        'status'        => $statusVal,
                        'auto_email'    => strtolower(trim($row[6] ?? '')) === 'enabled',
                        'cyan_level'    => is_numeric($row[7] ?? null) ? (int)$row[7] : null,
                        'magenta_level' => is_numeric($row[8] ?? null) ? (int)$row[8] : null,
                        'yellow_level'  => is_numeric($row[9] ?? null) ? (int)$row[9] : null,
                        'black_level'   => is_numeric($row[10] ?? null) ? (int)$row[10] : null,
                        'remarks'       => trim($row[11] ?? ''),
                    ];
                }
                fclose($handle);
            }

            // Simpan/update data printer aktif DAN simpan snapshot history
            foreach ($printerData as $data) {
                Printer::updateOrCreate(
                    ['name' => $data['name']],
                    [
                        'model'         => $data['model'],
                        'serial_number' => $data['serial_number'],
                        'location'      => $data['location'],
                        'total_pages'   => $data['total_pages'],
                        'status'        => $data['status'],
                        'auto_email'    => $data['auto_email'],
                        'cyan_level'    => $data['cyan_level'],
                        'magenta_level' => $data['magenta_level'],
                        'yellow_level'  => $data['yellow_level'],
                        'black_level'   => $data['black_level'],
                        'remarks'       => $data['remarks'],
                    ]
                );

                // Simpan snapshot untuk analytics
                PrinterSnapshot::create([
                    'import_history_id' => $importRecord->id,
                    'printer_name'      => $data['name'],
                    'model'             => $data['model'],
                    'serial_number'     => $data['serial_number'],
                    'location'          => $data['location'],
                    'status'            => $data['status'],
                    'total_pages'       => $data['total_pages'],
                    'cyan_level'        => $data['cyan_level'],
                    'magenta_level'     => $data['magenta_level'],
                    'yellow_level'      => $data['yellow_level'],
                    'black_level'       => $data['black_level'],
                    'auto_email'        => $data['auto_email'],
                    'remarks'           => $data['remarks'],
                    'imported_at'       => $importedAt,
                ]);

                $rows++;
            }

            // Update import record
            $importRecord->update([
                'imported_rows' => $rows,
                'status'        => 'success',
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            \Log::error('Import error: ' . $e->getMessage());

            if (isset($importRecord)) {
                $importRecord->update(['status' => 'failed', 'imported_rows' => $rows]);
            }
        }

        $msg = $status === 'success'
            ? "Import berhasil: {$rows} printer diproses dan disimpan ke history."
            : "Import gagal. Cek storage/logs/laravel.log untuk detail.";

        return redirect()->route('admin.import-history.index')->with('success', $msg);
    }
}