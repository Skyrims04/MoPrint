<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $fillable = [
        'name', 'model', 'serial_number', 'location', 'ip_address',
        'status', 'pages_today', 'total_pages', 'last_seen_at',
        'cyan_level', 'magenta_level', 'yellow_level', 'black_level',
        'auto_email', 'remarks'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}