<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use App\Http\Requests\StoreOrdenIngresoRequest;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\ListaPrecio;
use App\Models\OrdenIngreso;
use App\Models\PresentacionPollo;
use App\Models\Serie;
use App\Models\TipoPollo;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->withSum('detalle','cantidad_pollos')
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
        $order = OrdenIngreso::query()->create($request->validated() + ['user_id' => Auth::id()]);

        foreach ($request->collect('items') as $item){
            $order->detalle()->create($item);

            InventoryService::increment(
                $item['tipo_pollo_id'],
                $item['peso_neto'],
                $item['cantidad_pollos']
            );
        }

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
        $ordenIngreso->load('detalles');
        // Cambiar el estado a 'inactivo'
        $ordenIngreso->estado = false;
        $ordenIngreso->save();

        foreach ($ordenIngreso->detalle as $item){
            InventoryService::increment(
                array_get($item,'tipo_pollo_id'),
                array_get($item,'peso_neto'),
                array_get($item,'total_pollos'),
            );
        }

        // Retornar una respuesta JSON
        return response()->json(['message' => 'Orden cambiada a inactivo exitosamente.']);
    }
}
