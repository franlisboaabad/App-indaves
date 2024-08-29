<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Caja;
use App\Models\Pago;
use App\Models\Serie;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\MetodoPago;
use App\Models\ListaPrecio;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use App\Models\OrdenDespacho;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        return view('admin.ventas.index', compact('ventas', 'metodos'));
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


            try {
                // Generar el PDF
                $pdfFileNameA4 = $this->generatePdf_A4($venta->id);

                // Crear la ruta relativa para almacenar en el modelo
                $pdfFilePathA4 = 'ventasA4/' . $pdfFileNameA4;

                // Actualizar el modelo con la ruta del PDF
                $venta->url_venta_documento_a4 = $pdfFilePathA4;
                $venta->save();

                // Generar la URL pública
                $pdfUrlA4 = Storage::url($pdfFilePathA4);


                //Generar el Ticket
                $pdfFileNameTicket = $this->generateTicket($venta->id);

                // Crear la ruta relativa para almacenar en el modelo
                $pdfFilePathTicket = 'ventasTicket/' . $pdfFileNameTicket;

                // Actualizar el modelo con la ruta del PDF
                $venta->url_venta_documento_a4 = $pdfFilePathTicket;
                $venta->save();

                // Generar la URL pública
                $pdfUrlTicket = Storage::url($pdfFilePathTicket);

            } catch (\Exception $e) {
                //throw $th;
            }




            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta generada exitosamente.',
                'venta' => $venta,
                'urlPdf' => $pdfUrlA4,
                'urlTicket' => $pdfUrlTicket,
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
        // Pasa la venta a la vista
        return view('admin.ventas.show', compact('venta'));
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


    private function generatePdf_A4($id)
    {
        // Obtener la orden y la empresa
        $venta = Venta::find($id);
        if (!$venta) {
            throw new \Exception('Venta no encontrada.');
        }

        $empresa = Empresa::first();
        if (!$empresa) {
            throw new \Exception('Empresa no encontrada.');
        }

        $orden = OrdenDespacho::find($venta->orden_despacho_id);

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Habilitar PHP si es necesario
        $dompdf = new Dompdf($options);

        // Cargar la vista Blade
        $html = view('pdf.ventas.venta_a4', [
            'venta' => $venta,
            'empresa' => $empresa,
            'orden' => $orden
        ])->render();

        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Generar un nombre único para el archivo
        $fileName = 'venta_' . $id . '_' . now()->format('Ymd_His') . '.pdf';

        // Ruta donde se almacenará el archivo PDF
        $storagePath = 'public/ventasA4/' . $fileName;

        // Guardar el PDF en la carpeta especificada
        Storage::put($storagePath, $dompdf->output());

        return $fileName; // Devuelve el nombre del archivo generado
    }


    private function generateTicket($id)
    {
        // Obtener la orden y la empresa
        $venta = Venta::find($id);
        if (!$venta) {
            throw new \Exception('Venta no encontrada.');
        }

        $empresa = Empresa::first();
        if (!$empresa) {
            throw new \Exception('Empresa no encontrada.');
        }

        $orden = OrdenDespacho::find($venta->orden_despacho_id);

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Habilitar PHP si es necesario
        $dompdf = new Dompdf($options);

        // Cargar la vista Blade
        $html = view('pdf.ventas.venta_ticket', [
            'venta' => $venta,
            'empresa' => $empresa,
            'orden' => $orden
        ])->render();

        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Generar un nombre único para el archivo
        $fileName = 'venta_' . $id . '_' . now()->format('Ymd_His') . '.pdf';

        // Ruta donde se almacenará el archivo PDF
        $storagePath = storage_path('app/public/ventasTicket/' . $fileName);

        // Crear el directorio si no existe
        if (!is_dir(dirname($storagePath))) {
            mkdir(dirname($storagePath), 0755, true);
        }

        // Guardar el PDF en la carpeta especificada
        file_put_contents($storagePath, $dompdf->output());

        return $fileName; // Devuelve el nombre del archivo generado
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
