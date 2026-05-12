<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    protected $fillable = ['file_name', 'imported_rows', 'status'];

    public function snapshots()
    {
        return $this->hasMany(PrinterSnapshot::class);
    }
}