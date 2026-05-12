<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSnapshot extends Model
{
    protected $fillable = [
        'import_history_id', 'printer_name', 'model', 'serial_number',
        'location', 'status', 'total_pages', 'cyan_level', 'magenta_level',
        'yellow_level', 'black_level', 'auto_email', 'remarks', 'imported_at',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
        'auto_email'  => 'boolean',
    ];

    public function importHistory()
    {
        return $this->belongsTo(ImportHistory::class);
    }
}