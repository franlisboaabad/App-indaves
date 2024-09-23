<?php

namespace App\Services;

use App\Models\Inventory;
use Exception;

class InventoryService
{
    public static function increment($presentacionPollo,$tipoPollo,$totalPeso = 0,$totalPollos = 0): void
    {
        $inventory = Inventory::query()->firstOrNew([
            'presentacion_pollo_id' => $presentacionPollo,
            'tipo_pollo_id' => $tipoPollo
        ]);

        $inventory->total_peso += $totalPeso;
        $inventory->total_pollos += $totalPollos;
        $inventory->save();
    }


    public static function decrement($presentacionPollo,$tipoPollo,$totalPeso = 0,$totalPollos = 0): void
    {
        $inventory = Inventory::query()->firstOrNew([
            'presentacion_pollo_id' => $presentacionPollo,
            'tipo_pollo_id' => $tipoPollo
        ]);

        if($inventory->total_peso < $totalPeso){
            throw new Exception('El peso total excede el stock actual');
        }

        if($inventory->total_pollos < $totalPollos){
            throw new Exception('La cantidad de pollos excede el stock actual');
        }

        $inventory->total_peso -= $totalPeso;
        $inventory->total_pollos -= $totalPollos;
        $inventory->save();
    }

}
