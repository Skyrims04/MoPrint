<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TonerStock extends Model
{
    protected $fillable = [
        'printer_id', 'type', 'level', 'color_hex', 'stock_qty'
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}