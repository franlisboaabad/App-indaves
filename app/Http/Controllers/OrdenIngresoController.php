<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use App\Http\Requests\StoreOrdenIngresoRequest;
use App\Models\OrdenIngreso;
use Illuminate\Http\Request;

class OrdenIngresoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.ordenes-ingreso.index')->only('index');
        $this->middleware('can:admin.ordenes-ingreso.create')->only('create', 'store');
        $this->middleware('can:admin.ordenes-ingreso.edit')->only('edit', 'update');
        $this->middleware('can:admin.ordenes-ingreso.show')->only('show');
        $this->middleware('can:admin.ordenes-ingreso.destroy')->only('destroy');
    }


    public function index()
    {
        $ordenes_ingreso = OrdenIngreso::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->get();

        $cantidad_pollos_pendientes=  $ordenes_ingreso->sum('cantidad_pollos_stock');

        return view('admin.ordenes_ingreso.index', compact('ordenes_ingreso','cantidad_pollos_pendientes'));
    }


    public function create()
    {
        //
    }


    public function store(StoreOrdenIngresoRequest $request)
    {
        // Crear una nueva orden de ingreso
        OrdenIngreso::query()->create(['cantidad_pollos_stock' => $request->input('cantidad_pollos') ] + $request->validated());

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Orden registrada correctamente']);
    }


    public function show(OrdenIngreso $ordenIngreso)
    {
        //
    }

    public function edit(OrdenIngreso $ordenIngreso)
    {
        //
    }

    public function update(Request $request, OrdenIngreso $ordenIngreso)
    {
        //
    }

    public function destroy(OrdenIngreso $ordenIngreso)
    {
        // Verificar si la orden existe
        if (!$ordenIngreso) {
            return response()->json(['message' => 'Orden no encontrada.'], 404);
        }

        // Cambiar el estado a 'inactivo'
        $ordenIngreso->estado = false;
        $ordenIngreso->save();

        // Retornar una respuesta JSON
        return response()->json(['message' => 'Orden cambiada a inactivo exitosamente.']);
    }
}
