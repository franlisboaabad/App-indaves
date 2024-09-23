<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class InventoryController
{
    public function index()
    {
        $inventarios = Inventory::query()
            ->withAggregate('presentacion_pollo','descripcion')
            ->withAggregate('tipo_pollo','descripcion')
            ->get();

        return view('admin.inventarios.index',compact('inventarios'));
    }
}
