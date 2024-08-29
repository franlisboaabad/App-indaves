<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
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
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'venta_id' => 'required|exists:ventas,id',
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'monto' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener la venta y verificar si hay una caja abierta
        $venta = Venta::findOrFail($request->venta_id);
        $cajaAbierta = Caja::where('estado_caja', 1)->first();

        if (!$cajaAbierta) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una caja abierta.'
            ], 400); // Código de estado 400 para error de solicitud
        }

        // Crear el nuevo pago
        $pago = Pago::create([
            'venta_id' => $venta->id,
            'caja_id' => $cajaAbierta->id,
            'metodo_pago_id' => $request->metodo_pago_id,
            'monto' => $request->monto,
        ]);

        // Actualizar el estado de la venta si es necesario
        $venta->monto_recibido += $request->monto; // Acumulamos el monto recibido
        $venta->saldo -= $request->monto;

        if ($venta->saldo <= 0) {
            $venta->pagada = 1; // Cambiar estado si está completamente pagada
            $venta->saldo = 0; // Aseguramos que el saldo no sea negativo
        }

        $venta->save();

        // Actualizar el monto de la caja
        $cajaAbierta->increment('monto_cierre', $request->monto);

        return response()->json(['success' => true, 'message' => 'Pago agregado correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
