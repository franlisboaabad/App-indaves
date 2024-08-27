<?php

namespace App\Http\Controllers;

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
        $ordenes = OrdenDespacho::all();

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
        $serie = Serie::where('number', 'OD01')->first();
        $cajas = Caja::get();
        $clientes = Cliente::where('estado', 1)->get();
        $stockPollo = OrdenIngreso::orderBy('id','desc')->first();
        $tipoPollos = TipoPollo::where('estado',1)->get();


        return view('admin.ordenes_despacho.create', compact('clientes', 'cajas', 'serie','stockPollo','tipoPollos'));
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
            'presentacion_pollo' => 'required|numeric',
            'tipo_pollo_id' => 'required',
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
                'cantidad_pollos' => $request->cantidad_pollos,
                'peso_total_bruto' => $request->peso_total_bruto,
                'cantidad_jabas' => $request->cantidad_jabas,
                'tara' => $request->tara,
                'peso_total_neto' => $request->peso_total_neto,
                'presentacion_pollo' => $request->presentacion_pollo,
                'tipo_pollo_id' => $request->tipo_pollo_id
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
        // Obtener la orden de despacho con el ID dado
        $orden = OrdenDespacho::with('detalles')->findOrFail($ordenDespacho->id);

        // Pasar la orden y sus detalles a la vista
        return view('admin.ordenes_despacho.show', compact('orden'));
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

    private function generatePdf_A4($id)
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
}
