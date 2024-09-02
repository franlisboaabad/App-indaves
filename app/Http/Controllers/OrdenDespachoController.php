<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use App\Http\Requests\StoreOrdenDespachoRequest;
use App\Models\ListaPrecio;
use App\Models\PresentacionPollo;
use Dompdf\Dompdf;
use Dompdf\Options;


use App\Models\Caja;
use App\Models\Serie;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\OrdenDespacho;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOrdenDespacho;
use App\Models\OrdenIngreso;
use App\Models\TipoPollo;
use Illuminate\Support\Facades\Storage;


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
        // Obtener todas las órdenes de despacho
        $ordenes = OrdenDespacho::where('estado', 1)->get();

        // Convertir fechas a instancias de Carbon (si es necesario)
        foreach ($ordenes as $orden) {
            $orden->fecha_despacho = \Carbon\Carbon::parse($orden->fecha_despacho);
        }

        // Pasar las órdenes a la vista
        return view('admin.ordenes_despacho.index', compact('ordenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $serie = Serie::query()->where('number', 'OD01')->first();
        $cajas = Caja::get();
        $clientes = Cliente::query()->where('estado', 1)->get();
        $stockPollo = OrdenIngreso::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->sum('cantidad_pollos_stock');
        $tipoPollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacionPollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.ordenes_despacho.create',
            compact('clientes', 'cajas', 'serie', 'stockPollo', 'tipoPollos', 'presentacionPollos', 'prices')
        );
    }

    public function store(StoreOrdenDespachoRequest $request)
    {

        // Verificar si existe una caja abierta
        //  $cajaAbierta = Caja::where('estado_caja', 1)->first();
        $OrdenIngreso = OrdenIngreso::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        if (!$OrdenIngreso) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha registrado Orden de Ingreso',
            ], 400); // Código de estado 400 para error de solicitud
        }

        DB::beginTransaction(); // Iniciar una transacción

        try {
            // Crear la OrdenDespacho
            $ordenDespacho = OrdenDespacho::create([
                'cliente_id' => $request->cliente_id,
                'serie_orden' => $request->serie_orden,
                'fecha_despacho' => $request->fecha_despacho,
                'cantidad_pollos' => $request->cantidad_pollos,
                'peso_total_bruto' => $request->peso_total_bruto,
                'cantidad_jabas' => $request->cantidad_jabas,
                'tara' => $request->tara,
                'peso_total_neto' => $request->peso_total_neto,
                'presentacion_pollo' => $request->presentacion_pollo,
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
                    'peso_neto' => $detalle['peso_neto'],
                    'precio' => $detalle['precio'],
                    'tipo_pollo_id' => $detalle['tipo_pollo_id'],
                    'presentacion_pollo_id' => $detalle['presentacion_pollo_id'],
                ]);
            }

            //descontar cantidad de pollos en Orden de Ingreso

            $ordenIngreso = OrdenIngreso::orderBy('id', 'desc')->first();
            $ordenIngreso->cantidad_pollos_stock -= $request->cantidad_pollos;
            $ordenIngreso->save();


            // Generar los PDFs
            $pdfFileNameA4 = $this->generatePdf_A4($ordenDespacho->id);
            $pdfFilePathA4 = 'ordenesA4/' . $pdfFileNameA4;

            $pdfFileNameTicket = $this->generatePdf_Ticket($ordenDespacho->id);
            $pdfFilePathTicket = 'ordenesTicket/' . $pdfFileNameTicket;

            // Almacenar las rutas del PDF en el modelo
            $ordenDespacho->url_orden_documento_a4 = $pdfFilePathA4;
            $ordenDespacho->url_orden_documento_ticket = $pdfFilePathTicket;
            $ordenDespacho->save();

            // Generar las URL públicas
            $pdfUrlA4 = Storage::url($pdfFilePathA4);
            $pdfUrlTicket = Storage::url($pdfFilePathTicket);

            // Confirmar la transacción
            DB::commit();

            // Responder con éxito y proporcionar las URLs del PDF
            return response()->json([
                'message' => 'Orden de despacho registrada exitosamente.',
                'pdf_url_a4' => $pdfUrlA4,
                'pdf_url_ticket' => $pdfUrlTicket,
            ], 200);


        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error

            report($e);
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
     * @param \App\Models\OrdenDespacho $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenDespacho $ordenDespacho)
    {
        // Obtener la orden de despacho con el ID dado
        $orden = OrdenDespacho::query()
        ->with('detalles',
            fn($query)=>$query->withAggregate('tipo_pollo','descripcion')
                ->withAggregate('presentacion_pollo','descripcion')
        )->findOrFail($ordenDespacho->id);

        // Pasar la orden y sus detalles a la vista
        return view('admin.ordenes_despacho.show', compact('orden'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\OrdenDespacho $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenDespacho $ordenDespacho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OrdenDespacho $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenDespacho $ordenDespacho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OrdenDespacho $ordenDespacho
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenDespacho $ordenDespacho)
    {
        try {
            $orden = OrdenDespacho::findOrFail($ordenDespacho->id);


            $ordenIngreso = OrdenIngreso::orderBy('id', 'desc')->first();
            $ordenIngreso->cantidad_pollos_stock += $orden->cantidad_pollos;
            $ordenIngreso->save();


            // Cambiar el estado en lugar de eliminar el registro
            $orden->estado = 0; // O el valor que corresponda para marcar como despachado
            $orden->save();

            return response()->json(['success' => true, 'message' => 'Orden de despacho dado de baja.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function generatePdf_A4($id)
    {
        // Obtener la orden y la empresa
        $ordenDespacho = OrdenDespacho::query()
            ->with('detalles',
                fn($query)=>$query->withAggregate('tipo_pollo','descripcion')
                    ->withAggregate('presentacion_pollo','descripcion')
            )
            ->find($id);
        $empresa = Empresa::first(); // O el método que utilices para obtener la empresa

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Habilitar PHP si es necesario
        $dompdf = new Dompdf($options);

        // Cargar la vista Blade
        $html = view('pdf.orden_a4', [
            'orden' => $ordenDespacho,
            'empresa' => $empresa
        ])->render();

        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Generar un nombre único para el archivo
        $fileName = 'orden_despacho_' . $id . '.pdf';

        // Ruta donde se almacenará el archivo PDF
        $storagePath = storage_path('app/public/ordenesA4/' . $fileName);

        // Crear el directorio si no existe
        if (!is_dir(dirname($storagePath))) {
            mkdir(dirname($storagePath), 0755, true);
        }

        // Guardar el PDF en la carpeta especificada
        file_put_contents($storagePath, $dompdf->output());

        return $fileName; // Devuelve el nombre del archivo generado
    }


    private function generatePdf_Ticket($id)
    {
        // Obtener la orden y la empresa
        $ordenDespacho = OrdenDespacho::find($id);
        $empresa = Empresa::first(); // O el método que utilices para obtener la empresa

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Habilitar PHP si es necesario
        $dompdf = new Dompdf($options);

        // Cargar la vista Blade
        $html = view('pdf.orden_ticket', [
            'orden' => $ordenDespacho,
            'empresa' => $empresa
        ])->render();

        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Generar un nombre único para el archivo
        $fileName = 'orden_despacho_' . $id . '.pdf';

        // Ruta donde se almacenará el archivo PDF
        $storagePath = storage_path('app/public/ordenesTicket/' . $fileName);

        // Crear el directorio si no existe
        if (!is_dir(dirname($storagePath))) {
            mkdir(dirname($storagePath), 0755, true);
        }

        // Guardar el PDF en la carpeta especificada
        file_put_contents($storagePath, $dompdf->output());

        return $fileName; // Devuelve el nombre del archivo generado
    }


    //preview
    public function previewPdf($orderId)
    {
        // Obtener la orden desde la base de datos
        $orden = OrdenDespacho::findOrFail($orderId);
        $empresa = Empresa::first();

        // Configurar DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Cargar la vista y renderizar el PDF
        $view = view('pdf.ordenes_despacho.preview', compact('orden', 'empresa'))->render();
        $dompdf->loadHtml($view);

        // (Opcional) Configura el tamaño y orientación del papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Devolver el PDF como una vista previa en el navegador
        return $dompdf->stream('orden_preview.pdf', ['Attachment' => false]);
    }

}
