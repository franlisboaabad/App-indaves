<?php

namespace App\Http\Controllers;

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



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Ordenes_ingreso = OrdenIngreso::where('estado', 1)->get();

        return view('admin.ordenes_ingreso.index', compact('Ordenes_ingreso'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'cantidad_jabas' => 'required|integer',
            'cantidad_pollos' => 'required|integer',
            'peso_total' => 'required|numeric'
        ]);

        // Crear una nueva orden de ingreso
        OrdenIngreso::create(['cantidad_pollos_stock' => $request->cantidad_pollos ] + $request->all());

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Orden registrada correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenIngreso  $ordenIngreso
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenIngreso $ordenIngreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrdenIngreso  $ordenIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenIngreso $ordenIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenIngreso  $ordenIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenIngreso $ordenIngreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrdenIngreso  $ordenIngreso
     * @return \Illuminate\Http\Response
     */
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
