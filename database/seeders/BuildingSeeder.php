<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['name' => 'BAT 1', 'code' => 'BAT1', 'location' => 'Gedung BAT 1'],
            ['name' => 'BAT 2', 'code' => 'BAT2', 'location' => 'Gedung BAT 2'],
            ['name' => 'BAT 3', 'code' => 'BAT3', 'location' => 'Gedung BAT 3'],
        ];

        foreach ($buildings as $data) {
            $building = Building::firstOrCreate(['code' => $data['code']], $data);
            $building->initializeTonerStocks(); // buat 4 toner CMYK otomatis
        }
    }
}