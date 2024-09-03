<?php

namespace App\Http\Controllers;

use App\CoreDevPro\Template;
use App\Enums\GlobalStateEnum;
use App\Http\Requests\StoreVentaRequest;
use App\Models\Caja;
use App\Models\OrdenIngreso;
use App\Models\Pago;
use App\Models\PresentacionPollo;
use App\Models\Saldo;
use App\Models\Serie;
use App\Models\TipoPollo;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\MetodoPago;
use App\Models\ListaPrecio;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use App\Models\OrdenDespacho;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Mpdf\Mpdf;

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
        $stockPollo = OrdenIngreso::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->sum('cantidad_pollos_stock');
        $tipoPollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacionPollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $prices = ListaPrecio::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        $clientes = Cliente::query()
            ->where('estado', GlobalStateEnum::STATUS_ACTIVE)
            ->withSum('saldos','total')
            ->get();

        return view('admin.ventas.create',
            compact( 'cajas', 'serie', 'precio', 'metodos',
                'stockPollo','tipoPollos','presentacionPollos','clientes','prices')
        );
    }

    public function store(StoreVentaRequest $request)
    {
        // Verificar si existe una caja abierta
        $cajaAbierta = Caja::query()->where('estado_caja', GlobalStateEnum::STATUS_ACTIVE)->first();

        if (!$cajaAbierta) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una caja abierta.',
            ], 422); // Código de estado 400 para error de solicitud
        }

        // Iniciar la transacción para garantizar la integridad de los datos
        DB::beginTransaction();

        try {

            $monto_recibido = $request->input('monto_recibido', 0);
            $monto_total = $request->input('monto_total');
            $saldo = $monto_total - $monto_recibido;
            $pagada = $saldo <= 0;

            // Crear la nueva venta
            $venta = Venta::query()->create([
                'cliente_id' => $request->input('cliente_id'),
                'serie_venta' => $request->input('serie_venta'),
                'fecha_venta' => $request->input('fecha_venta'),
                'peso_neto' => $request->input('peso_total_neto'),
                'forma_de_pago' => $request->input('forma_de_pago'),
                'metodo_pago_id' => $request->input('metodo_pago_id'),
                'monto_total' => $request->input('monto_total'),
                'monto_recibido' => $request->input('monto_recibido'),
                'saldo' => $saldo,
                'pagada' => $pagada
            ]);
            //serie
            // Aumentar el número de serie (1) nota de venta | 2 orden despacho
            $this->incrementarSerieVenta();

            $ordenIngreso = OrdenIngreso::orderBy('id', 'desc')->first();
            // Verificar si se encontró un registro
            if ($ordenIngreso) {
                // Asegurarse de que la cantidad a restar no sea mayor que el stock disponible
                if ($ordenIngreso->cantidad_pollos_stock >= $request->cantidad_pollos) {
                    // Restar la cantidad de pollos del stock
                    $ordenIngreso->cantidad_pollos_stock -= $request->cantidad_pollos;
                    // Guardar los cambios en la base de datos
                    $ordenIngreso->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock de pollos.',
                    ], 422); // Código de estado 400 para error de solicitud
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró ningún registro de orden de ingreso para actualizar.',
                ], 422); // Código de estado 400 para error de solicitud
            }

            // Crear el detalle de la venta
            // Encuentra la orden de despacho relacionada
            $ordenDespacho = OrdenDespacho::query()->find($venta->orden_despacho_id);

            foreach ($request->detalles as $detalle) {
                DetalleVenta::query()->create([
                    'venta_id' => $venta->getKey(),
                    'cantidad_pollos' => $detalle['cantidad_pollos'],
                    'peso_bruto' => $detalle['peso_bruto'],
                    'cantidad_jabas' => $detalle['cantidad_jabas'],
                    'tara' => $detalle['tara'],
                    'peso_neto' => $detalle['peso_neto'],
                    'precio' => $detalle['precio'],
                    'subtotal' => $detalle['subtotal'],
                    'tipo_pollo_id' => $detalle['tipo_pollo_id'],
                    'presentacion_pollo_id' => $detalle['presentacion_pollo_id'],
                ]);
            }



            if ($request->input('forma_de_pago') == Pago::FORMA_PAGO_CONTADO) {

                // Registrar el pago
                $pago = new Pago();
                $pago->venta_id = $venta->id;
                $pago->caja_id = $cajaAbierta->id;
                $pago->metodo_pago_id = $venta->metodo_pago_id;
                $pago->monto = $venta->monto_recibido;
                $pago->save();


                if($request->input('saldo_pendiente')> 0){
                    // Registrar el pago
                    $pago = new Pago();
                    $pago->venta_id = $venta->id;
                    $pago->caja_id = $cajaAbierta->id;
                    $pago->metodo_pago_id = MetodoPago::METODO_PAGO_SALDO;
                    $pago->monto = $request->input('saldo_pendiente');
                    $pago->save();

                    $cajaAbierta->monto_cierre += $pago->monto;
                }
                // Actualizar el monto de la caja
                $cajaAbierta->monto_cierre += $pago->monto;
                $cajaAbierta->save();
            }

            if($saldo < 0 ){
                Saldo::query()->create([
                    'cliente_id' => $venta->cliente_id,
                    'reference_id' => $venta->getKey(),
                    'total' => abs($saldo)
                ]);
            }

            $venta->url_pdf =  route('ventas.print',['id' => $venta->getKey(),'format' => 'a4']);
            $venta->url_ticket = route('ventas.print',['id' => $venta->getKey(),'format' => 'ticket']);
            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta generada exitosamente.',
                'venta' => $venta
            ], 200);

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            report($e);
            // Manejo de errores si algo sale mal al guardar
            return response()->json([
                'success' => false,
                'message' => 'Hubo un problema al generar la venta. Inténtalo de nuevo.',
                'error' => $e->getMessage() // Incluye el mensaje de error para depuración
            ], 500); // Código de estado 500 para errores del servidor
        }
    }

    public function show(Venta $venta)
    {
        // Pasa la venta a la vista
        return view('admin.ventas.show', compact('venta'));
    }


    public function edit(Venta $venta)
    {
        //
    }


    public function update(Request $request, Venta $venta)
    {
        //
    }


    public function destroy(Venta $venta)
    {
        //
    }


    private function generatePdf_A4($id)
    {
        // Obtener la orden y la empresa
        $empresa = Empresa::query()->firstOrFail();
        $venta = Venta::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('pagos',function($query){
                $query->withAggregate('metodo_pago', 'descripcion');
            })
            ->with('cliente', fn($query) => $query->withSum('saldos', 'total'))
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($id);


        $pdf = Pdf::loadView('pdf.ventas.venta_a4',[
            'venta' => $venta,
            'empresa' => $empresa
        ]);

        return $pdf->stream();
    }


    private function generateTicket($id)
    {
        // Obtener la orden y la empresa
        $empresa = Empresa::query()->firstOrFail();
        $venta = Venta::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('cliente', fn($query) => $query->withSum('saldos', 'total'))
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )->findOrFail($id);

        ini_set('pcre.backtrack_limit', '5000000');

        $html = view('pdf.ventas.venta_ticket',
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


    public function getOrdenDetalles($id)
    {
        $orden = OrdenDespacho::query()
            ->withAggregate('cliente', 'razon_social')
            ->with('cliente', fn($query) => $query->withSum('saldos', 'total'))
            ->with('detalles',
                fn($query) => $query->withAggregate('tipo_pollo', 'descripcion')
                    ->withAggregate('presentacion_pollo', 'descripcion')
            )
            ->findOrFail($id);

        if ($orden) {
            return response()->json([
                'orden' => $orden
            ]);
        }

        return response()->json(null, 404);
    }


    public function print($venta, $format)
    {
        return $format == 'ticket' ? $this->generateTicket($venta) : $this->generatePdf_A4($venta);
    }

    private function incrementarSerieVenta(): void
    {
        $serie = Serie::query()->findOrFail(1);
        $serie->serie = $serie->serie + 1;
        $serie->save();
    }

}
