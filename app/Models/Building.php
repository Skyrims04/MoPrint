<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['name', 'code', 'location', 'description'];

    public function tonerStocks()
    {
        return $this->hasMany(BuildingTonerStock::class)
            ->orderByRaw("CASE type WHEN 'Cyan' THEN 1 WHEN 'Magenta' THEN 2 WHEN 'Yellow' THEN 3 WHEN 'Black' THEN 4 ELSE 5 END");
    }

    public function initializeTonerStocks(): void
    {
        $defaults = [
            ['type' => 'Cyan',    'color_hex' => '#00bcd4'],
            ['type' => 'Magenta', 'color_hex' => '#e91e63'],
            ['type' => 'Yellow',  'color_hex' => '#ffc107'],
            ['type' => 'Black',   'color_hex' => '#607d8b'],
        ];

        foreach ($defaults as $toner) {
            BuildingTonerStock::firstOrCreate(
                ['building_id' => $this->id, 'type' => $toner['type']],
                ['color_hex' => $toner['color_hex'], 'stock_qty' => 0, 'threshold' => 5]
            );
        }
    }
}