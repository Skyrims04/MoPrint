<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingTonerStock extends Model
{
    protected $fillable = [
        'building_name',
        'type',
        'color_hex',
        'stock_qty',
        'threshold',
    ];

    public function isLow(): bool
    {
        return $this->stock_qty < $this->threshold;
    }

    public function barPercent(): int
    {
        if ($this->stock_qty <= 0) return 0;
        return min(100, (int) round(($this->stock_qty / max($this->stock_qty, 30)) * 100));
    }
}