<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Serie;
use App\Models\Cliente;
use App\Models\Inventory;
use App\Models\TipoPollo;
use App\Models\ListaPrecio;
use App\Models\OrdenIngreso;
use Illuminate\Http\Request;
use App\Enums\GlobalStateEnum;
use App\Models\PresentacionPollo;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOrdenIngreso;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrdenIngresoRequest;

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
            ->withSum('detalle', 'cantidad_pollos')
            ->get();

        $cantidad_pollos_pendientes = $ordenes_ingreso->sum('cantidad_pollos_stock');

        return view('admin.ordenes_ingreso.index', compact('ordenes_ingreso', 'cantidad_pollos_pendientes'));
    }


    public function create()
    {
        $clientes = Cliente::query()->where('estado', 1)->get();
        $tipo_pollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacion_pollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.ordenes_ingreso.create', compact('clientes',  'tipo_pollos', 'presentacion_pollos', 'prices'));
    }


    public function store(StoreOrdenIngresoRequest $request)
    {
        /** @var OrdenIngreso $order */
        $order = OrdenIngreso::query()->create($request->validated() +
            ['user_id' => Auth::id(), 'fecha_ingreso' => now()]);

        foreach ($request->collect('items') as $item) {
            $order->detalle()->create($item);

            InventoryService::increment(
                $item['presentacion_pollo_id'],
                $item['tipo_pollo_id'],
                $item['peso_neto'],
                $item['cantidad_pollos'],
                $request->input('tipo_ingreso')
            );
        }

        return response()->json(['message' => 'Orden registrada correctamente']);
    }


    public function show(OrdenIngreso $ordenIngreso)
    {
        $ordenIngreso = OrdenIngreso::with('detalle')->findOrFail($ordenIngreso->id);
        return view('admin.ordenes_ingreso.show', compact('ordenIngreso'));
    }

    public function edit(OrdenIngreso $ordenIngreso)
    {
        $orden = OrdenIngreso::with('detalle')->find($ordenIngreso->id);
        return view('admin.ordenes_ingreso.edit', compact('orden'));
    }

    public function update(Request $request, OrdenIngreso $ordenIngreso)
    {

        DB::beginTransaction(); // Iniciar la transacción

        try {
            // Encuentra la orden
            $orden = OrdenIngreso::findOrFail($ordenIngreso->id);

             // Validar si la fecha de la orden es del día de hoy
            if ($orden->created_at->toDateString() !== now()->toDateString()) {
                return response()->json(['success' => false, 'message' => 'No se puede actualizar una orden de ingreso de una fecha pasada.'], 400);
            }


            // Actualiza la orden con los datos del request
            $orden->update($request->only(['peso_bruto', 'peso_tara', 'peso_neto']));

            // Actualiza los detalles de la orden
            foreach ($request->detalle as $detalleData) {
                $detalle = DetalleOrdenIngreso::findOrFail($detalleData['id']);

                // Guardamos los valores anteriores para actualizar el inventario correctamente
                $anteriorCantidadPollos = $detalle->cantidad_pollos;
                $anteriorPesoNeto = $detalle->peso_neto;

                // Actualiza el detalle
                $detalle->update($detalleData);

                // Actualizar el inventario
                $inventory = Inventory::where('presentacion_pollo_id', $detalle->presentacion_pollo_id)
                    ->where('tipo_pollo_id', $detalle->tipo_pollo_id)
                    ->where('tipo_ingreso', $orden->tipo_ingreso)
                    ->first();

                if ($inventory) {
                    // Ajustar el inventario
                    $inventory->total_pollos += ($detalleData['cantidad_pollos'] - $anteriorCantidadPollos); // Ajusta por la diferencia
                    $inventory->total_peso += ($detalleData['peso_neto'] - $anteriorPesoNeto); // Ajusta por la diferencia
                    $inventory->save();
                }
            }

            DB::commit(); // Confirmar la transacción
            return response()->json(['success' => true, 'message' => 'Orden actualizada correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return response()->json(['success' => false, 'message' => 'Error al actualizar la orden: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(OrdenIngreso $ordenIngreso)
    {
        $ordenIngreso->load('detalle');
        // Cambiar el estado a 'inactivo'
        $ordenIngreso->estado = false;
        $ordenIngreso->save();

        foreach ($ordenIngreso->detalle as $detalle) {
            InventoryService::decrement(
                $detalle->presentacion_pollo_id,
                $detalle->tipo_pollo_id,
                $detalle->peso_neto,
                $detalle->cantidad_pollos,
                $ordenIngreso->tipo_ingreso
            );
        }

        // Retornar una respuesta JSON
        return response()->json(['message' => 'Orden cambiada a inactivo exitosamente.']);
    }
}
