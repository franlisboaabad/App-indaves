<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use App\Http\Requests\StoreOrdenDespachoRequest;
use App\Models\Inventory;
use App\Models\ListaPrecio;
use App\Models\MetodoPago;
use App\Models\PresentacionPollo;
use App\Models\Venta;
use App\Services\InventoryService;
use App\Services\SeriesService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
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
use Mpdf\Mpdf;


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
        $stockPollo = Inventory::query()->get();
        $tipoPollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacionPollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.ordenes_despacho.create', compact('clientes', 'cajas', 'serie', 'stockPollo', 'tipoPollos', 'presentacionPollos', 'prices'));
    }

    public function store(StoreOrdenDespachoRequest $request)
    {
        DB::beginTransaction();

        $subTotal  = $request->collect('detalles')->sum('subtotal');
        $createNotaIngreso = $request->boolean('generar_nota_ingreso');
        $presentacionPolloBeneficiado = PresentacionPollo::query()->findOrFail(TipoPollo::POLLO_BENEFICIADO_ID);
        try {
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
                'estado_despacho' => OrdenDespacho::ESTADO_DESPACHADO,
                'subtotal' => $subTotal
            ]);

            SeriesService::increment(Serie::DEFAULT_SERIE_DESPACHO);

            foreach ($request->collect('detalles') as $detalle) {
                DetalleOrdenDespacho::query()->create([
                    'orden_despacho_id' => $ordenDespacho->id,
                    'cantidad_pollos' => $detalle['cantidad_pollos'],
                    'peso_bruto' => $detalle['peso_bruto'],
                    'cantidad_jabas' => $detalle['cantidad_jabas'],
                    'tara' => $detalle['tara'],
                    'peso_neto' => $detalle['peso_neto'],
                    'precio' => $detalle['precio'] ?? 0,
                    'subtotal' => $detalle['subtotal'] ?? 0,
                    'tipo_pollo_id' => $detalle['tipo_pollo_id'],
                    'presentacion_pollo_id' => $detalle['presentacion_pollo_id'],
                ]);

                InventoryService::decrement(
                    $detalle['presentacion_pollo_id'],
                    $detalle['tipo_pollo_id'],
                    $detalle['peso_neto'],
                    $detalle['cantidad_pollos']
                );

                if($createNotaIngreso){
                    InventoryService::increment(
                        $presentacionPolloBeneficiado->getKey(),
                        $detalle['tipo_pollo_id'],
                        $detalle['peso_neto'],
                        $detalle['cantidad_pollos']
                    );
                }
            }

            // Confirmar la transacción
            DB::commit();

            $ordenDespacho->url_pdf = route('ordenes-de-despacho.print', ['id' => $ordenDespacho->getKey(), 'format' => 'a4']);
            $ordenDespacho->url_ticket = route('ordenes-de-despacho.print', ['id' => $ordenDespacho->getKey(), 'format' => 'ticket']);

            return response()->json([
                'message' => 'Orden de despacho registrada exitosamente.',
                'data' => $ordenDespacho
            ], 200);


        } catch (\Exception $e) {
            DB::rollBack();

            report($e);
            // Responder con error
            return response()->json([
                'message' => 'Error al registrar la orden de despacho.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(OrdenDespacho $ordenDespacho)
    {
        $orden = OrdenDespacho::query()
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($ordenDespacho->id);

        return view('admin.ordenes_despacho.show', compact('orden'));
    }


    public function edit(OrdenDespacho $ordenDespacho)
    {

        $serie = Serie::query()->where('number', 'OD01')->first();
        $cajas = Caja::get();
        $clientes = Cliente::query()->where('estado', 1)->get();
        $stockPollo = Inventory::query()->get();
        $tipoPollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacionPollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.ordenes_despacho.edit', compact('clientes', 'cajas', 'serie', 'stockPollo', 'tipoPollos', 'presentacionPollos', 'prices'));
    }

    public function update(Request $request, OrdenDespacho $ordenDespacho)
    {
        //
    }


    public function destroy(OrdenDespacho $ordenDespacho)
    {
        try {

            DB::beginTransaction();
            $ordenDespacho->load('detalles');
            foreach ($ordenDespacho->detalles as $detalle) {
                InventoryService::increment(
                    $detalle->presentacion_pollo_id,
                    $detalle->tipo_pollo_id,
                    $detalle->peso_neto,
                    $detalle->cantidad_pollos,
                );
            }

            $ordenDespacho->estado = OrdenDespacho::ESTADO_INACTIVE;
            $ordenDespacho->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Orden de despacho dado de baja.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function print($orden, $format)
    {

        return strtolower($format) == 'ticket' ? $this->generateTicket($orden) : $this->generatePdf_A4($orden);
    }


    public function venta($id)
    {
        $serie = Serie::query()->first();
        $cajas = Caja::get();
        $precio = ListaPrecio::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->latest('id')
            ->first();
        $metodos = MetodoPago::get();
        $stockPollo = Inventory::query()->get();
        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        $orden = OrdenDespacho::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($id);

        $cliente = Cliente::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->withSum('saldos','total')
            ->where('id',$orden->cliente_id)
            ->first();

        $orden->detalles?->each(function($detalle) use($prices){
            $detalle->precio = $prices->where('tipo_pollo_id',$detalle->tipo_pollo_id)->first()?->precio;
            $detalle->subtotal = $detalle->precio * $detalle->peso_neto;
            $detalle->peso_neto = floatval($detalle->peso_neto);
            $detalle->descuento_peso = 0;
        });

        $cliente->setAttribute('deuda_pendiente', Venta::query()->where('cliente_id', $orden->cliente_id)->sum('monto_pendiente'));

        return view('admin.ventas.create-despacho',
            compact( 'cajas', 'serie', 'precio', 'metodos',
                'stockPollo','prices','orden','cliente'));
    }

    private function generatePdf_A4($id)
    {
        // Obtener la orden y la empresa
        $empresa = Empresa::query()->firstOrFail();
        $venta = OrdenDespacho::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('cliente', fn($query) => $query->withSum('saldos', 'total'))
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($id);

        $pdf = Pdf::loadView('pdf.ordenes_despacho.orden_a4', [
            'venta' => $venta,
            'empresa' => $empresa
        ]);

        return $pdf->stream();
    }

    private function generateTicket($id)
    {
        // Obtener la orden y la empresa
        $empresa = Empresa::query()->firstOrFail();
        $venta = OrdenDespacho::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('cliente', fn($query) => $query->withSum('saldos', 'total'))
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($id);

        ini_set('pcre.backtrack_limit', '5000000');

        $html = view('pdf.ordenes_despacho.orden_ticket',
            ['venta' => $venta,
                'empresa' => $empresa
            ]
        )->render();

        $company_name = (strlen($empresa->name) / 20) * 10;
        $quantity_rows = count($venta->detalles);
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [
                78,
                210 +
                14 +
                ($quantity_rows * 8) +
                $company_name
            ],
            'margin_top' => 2,
            'margin_right' => 5,
            'margin_bottom' => 0,
            'margin_left' => 5,
        ]);

        $pdf->WriteHTML($html);

        return $pdf->Output();
    }
}
