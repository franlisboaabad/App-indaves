<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{
    public static function increment($tipoPollo,$totalPeso = 0,$totalPollos = 0): void
    {
        $inventory = Inventory::query()->firstOrNew([
            'tipo_pollo_id' => $tipoPollo
        ]);

        $inventory->total_peso += $totalPeso;
        $inventory->total_pollos += $totalPollos;
        $inventory->save();
    }


    public static function decrement($tipoPollo,$totalPeso = 0,$totalPollos = 0): void
    {
        $inventory = Inventory::query()->firstOrNew([
            'tipo_pollo_id' => $tipoPollo
        ]);

        $inventory->total_peso -= $totalPeso;
        $inventory->total_pollos -= $totalPollos;
        $inventory->save();
    }

}
