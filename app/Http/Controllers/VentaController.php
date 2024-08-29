<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Pago;
use App\Models\Serie;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Models\ListaPrecio;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use App\Models\OrdenDespacho;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with('ordenDespacho') // Incluye relaciones si es necesario
            ->orderBy('fecha_venta', 'desc') // Ordenar por fecha de venta
            ->get();
        $metodos = MetodoPago::get();

        return view('admin.ventas.index', compact('ventas','metodos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $serie = Serie::first();
        $cajas = Caja::get();
        $precio = ListaPrecio::where('estado', 1)
            ->latest('id') // Ordenar en orden descendente por el campo `id`
            ->first(); // Obtener el primer registro de la lista ordenada
        $metodos = MetodoPago::get();
        $ordenes = OrdenDespacho::where('estado_despacho', 0)->get();


        return view('admin.ventas.create', compact('ordenes', 'cajas', 'serie', 'precio', 'metodos'));
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
        $validator = Validator::make($request->all(), [
            'orden_despacho_id' => 'required|exists:orden_despachos,id',
            'serie_venta' => 'required|string|max:255',
            'fecha_venta' => 'required|date',
            'peso_neto' => 'required|numeric',
            'forma_de_pago' => 'required|in:0,1', // 0: CONTADO ; 1 A CREDITO
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'monto_total' => 'required|numeric',
            'monto_recibido' => 'required|numeric',
        ]);

        // Si la validación falla, devolver una respuesta de error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor corrija los errores.',
                'errors' => $validator->errors()
            ], 422); // Código de estado 422 para errores de validación
        }

        // Verificar si existe una caja abierta
        $cajaAbierta = Caja::where('estado_caja', 1)->first();

        if (!$cajaAbierta) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una caja abierta.',
            ], 400); // Código de estado 400 para error de solicitud
        }

        // Iniciar la transacción para garantizar la integridad de los datos
        DB::beginTransaction();

        try {


            $monto_recibido = $request->input('monto_recibido', 0);
            $monto_total = $request->input('monto_total');
            $saldo = $monto_total - $monto_recibido;
            $pagada = $saldo <= 0;

            // Crear la nueva venta
            $venta = Venta::create([
                'orden_despacho_id' => $request->input('orden_despacho_id'),
                'serie_venta' => $request->input('serie_venta'),
                'fecha_venta' => $request->input('fecha_venta'),
                'peso_neto' => $request->input('peso_neto'),
                'forma_de_pago' => $request->input('forma_de_pago'),
                'metodo_pago_id' => $request->input('metodo_pago_id'),
                'monto_total' => $request->input('monto_total'),
                'monto_recibido' => $request->input('monto_recibido'),
                'saldo' => $saldo,
                'pagada' => $pagada
            ]);

            //serie
            // Aumentar el número de serie (1) nota de venta | 2 orden despacho
            $serie = Serie::findOrFail(1);
            $serie->serie = $serie->serie + 1;
            $serie->save();

            // Crear el detalle de la venta
            // Encuentra la orden de despacho relacionada
            $ordenDespacho = OrdenDespacho::find($venta->orden_despacho_id);

            $detalleVenta = DetalleVenta::create([
                'venta_id' => $venta->id,
                'orden_despacho_id' => $venta->orden_despacho_id,
                'cantidad_pollos' => $ordenDespacho->cantidad_pollos,
                'peso_bruto' => $ordenDespacho->peso_total_bruto,
                'cantidad_jabas' => $ordenDespacho->cantidad_jabas,
                'tara' => $ordenDespacho->tara,
                'peso_neto' => $ordenDespacho->peso_total_neto,
            ]);

            // Actualizar el estado de la orden de despacho
            // $ordenDespacho = OrdenDespacho::findOrFail($venta->orden_despacho_id);
            $ordenDespacho->estado_despacho = 1;
            $ordenDespacho->save();

            if ($request->forma_de_pago == 0) {

                // Registrar el pago
                $pago = new Pago();
                $pago->venta_id = $venta->id;
                $pago->caja_id = $cajaAbierta->id;
                $pago->metodo_pago_id = $venta->metodo_pago_id;
                $pago->monto = $venta->monto_total;
                $pago->save();

                // Actualizar el monto de la caja
                $cajaAbierta->monto_cierre += $pago->monto;
                $cajaAbierta->save();

            }



            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta generada exitosamente.',
                'venta' => $venta
            ], 200); // Código de estado 200 para éxito

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Manejo de errores si algo sale mal al guardar
            return response()->json([
                'success' => false,
                'message' => 'Hubo un problema al generar la venta. Inténtalo de nuevo.',
                'error' => $e->getMessage() // Incluye el mensaje de error para depuración
            ], 500); // Código de estado 500 para errores del servidor
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }


    public function getOrdenDetalles($id)
    {
        $orden = OrdenDespacho::with('detalles')->find($id);

        if ($orden) {
            return response()->json([
                'detalles' => $orden->detalles
            ]);
        }

        return response()->json(null, 404);
    }
}
