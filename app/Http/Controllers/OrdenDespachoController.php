<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Serie;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\OrdenDespacho;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOrdenDespacho;

class OrdenDespachoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.ordenes-despacho.index')->only('index');
        $this->middleware('can:admin.ordenes-despacho.create')->only('create', 'store');
        $this->middleware('can:admin.ordenes-despacho.edit')->only('edit', 'update');
        $this->middleware('can:admin.ordenes-despacho.show')->only('show');
        $this->middleware('can:admin.ordenes-despacho.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $serie = Serie::where('number', 'OD01')->first();
        $cajas = Caja::get();
        $clientes = Cliente::where('estado', 1)->get();
        return view('admin.ordenes_despacho.create', compact('clientes', 'cajas', 'serie'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'cliente_id' => 'required|integer',
            'serie_orden' => 'required|string|max:255',
            'fecha_despacho' => 'required|date',
            'peso_total_bruto' => 'required|numeric',
            'cantidad_jabas' => 'required|integer',
            'tara' => 'required|numeric',
            'peso_total_neto' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.cantidad_pollos' => 'required|numeric',
            'detalles.*.peso_bruto' => 'required|numeric',
            'detalles.*.cantidad_jabas' => 'required|integer',
            'detalles.*.tara' => 'required|numeric',
            'detalles.*.peso_neto' => 'required|numeric',
        ]);

        DB::beginTransaction(); // Iniciar una transacción

        try {
            // Crear la OrdenDespacho
            $ordenDespacho = OrdenDespacho::create([
                'cliente_id' => $request->cliente_id,
                'serie_orden' => $request->serie_orden,
                'fecha_despacho' => $request->fecha_despacho,
                'peso_total_bruto' => $request->peso_total_bruto,
                'cantidad_jabas' => $request->cantidad_jabas,
                'tara' => $request->tara,
                'peso_total_neto' => $request->peso_total_neto,
            ]);

            // Aumentar el número de serie
            $serie = Serie::findOrFail(2);
            $serie->serie = $serie->serie + 1;
            $serie->save();

            // Guardar los detalles
            foreach ($request->detalles as $detalle) {
                DetalleOrdenDespacho::create([
                    'orden_despacho_id' => $ordenDespacho->id,
                    'cantidad_pollos' => $detalle['cantidad_pollos'],
                    'peso_bruto' => $detalle['peso_bruto'],
                    'cantidad_jabas' => $detalle['cantidad_jabas'],
                    'tara' => $detalle['tara'],
                    'peso_neto' => $detalle['peso_neto']
                ]);
            }

            DB::commit(); // Confirmar la transacción

            // Responder con éxito
            return response()->json(['message' => 'Orden de despacho registrada exitosamente.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            // Responder con error
            return response()->json([
                'message' => 'Error al registrar la orden de despacho.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenDespacho  $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenDespacho $ordenDespacho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrdenDespacho  $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenDespacho $ordenDespacho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenDespacho  $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenDespacho $ordenDespacho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrdenDespacho  $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenDespacho $ordenDespacho)
    {
        //
    }
}
