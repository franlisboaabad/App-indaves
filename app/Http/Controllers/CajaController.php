<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use App\Exports\IngresosExport;
use App\Exports\SummaryExport;
use App\Models\Empresa;
use App\Models\OrdenDespacho;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use App\Models\Caja;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.cajas.index', compact('cajas'));
    }

    public function create()
    {
        return view('admin.cajas.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'monto_apertura' => 'required|numeric',
        ]);

        // Puedes añadir aquí cualquier lógica adicional para el usuario
        $userId = auth()->id(); // Obtener el ID del usuario autenticado

        Caja::create([
            'user_id' => $userId,
            'monto_apertura' => $request->monto_apertura,
            'fecha_apertura' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function show(Caja $caja)
    {
        $caja = Caja::query()
            ->with('pagos', function (HasMany $query) {
                $query->with('venta', fn(BelongsTo $q) => $q->withAggregate('cliente', 'razon_social'));
            })
            ->findOrFail($caja->id);

        $caja->fecha_cierre = $caja->fecha_cierre ? Carbon::parse($caja->fecha_cierre) : null;

        $reports = $this->getSummary($caja);

        return view('admin.cajas.show', compact('caja', 'reports'));
    }

    public function edit(Caja $caja)
    {
        //
    }

    public function update(Request $request, Caja $caja)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'fecha_cierre' => 'nullable|date',
            ]);

            // Buscar la caja por su ID
            $caja = Caja::findOrFail($caja->id);

            // Actualizar los campos necesarios
            $caja->estado_caja = 0;
            $caja->fecha_cierre = $validated['fecha_cierre'] ? Carbon::parse($validated['fecha_cierre']) : null;

            // Guardar los cambios
            $caja->save();

            // Devolver una respuesta JSON
            return response()->json([
                'success' => true,
                'message' => 'Caja actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Loguear el error
            Log::error('Error al actualizar la caja: ' . $e->getMessage());

            // Devolver una respuesta JSON con error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la caja.'
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }


    public function destroy(Caja $caja)
    {
        try {
            // Buscar la caja por su ID
            $caja = Caja::findOrFail($caja->id);

            // Eliminar la caja
            $caja->estado = 0;
            $caja->estado_caja = 0;
            $caja->save();

            // Devolver una respuesta JSON
            return response()->json([
                'success' => true,
                'message' => 'Caja eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Loguear el error
            Log::error('Error al eliminar la caja: ' . $e->getMessage());

            // Devolver una respuesta JSON con error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la caja.'
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }

    public function summary(Request $request,Caja $caja, $format)
    {
        $reports = $this->getSummary($caja);
        $empresa = Empresa::query()->firstOrFail();

        if($format == 'pdf'){
            $pdf = Pdf::loadView('pdf.cajas.print_a4',[
                'reports' => $reports,
                'caja'  => $caja,
                'empresa' => $empresa
            ])->setPaper('a4', 'landscape');

            return $pdf->stream();
        }


        return (new SummaryExport())
            ->excel_view('pdf.cajas.print_excel')
            ->records($reports)
            ->setCompany($empresa)
            ->download('REPORTE-RESUMEN' . Carbon::now() . '.xlsx');

    }


    private function getSummary($caja): array
    {
        DB::statement("SET sql_mode = '' ");
        return DB::select("
            SELECT
            cli.razon_social as cliente,
            sum(de.cantidad_pollos) as cantidad_pollos,
            sum(de.peso_total_neto) as peso_total_neto,
            round(SUM(de.peso_total_bruto / de.cantidad_pollos),2) as promedio,
            (SELECT ROUND(avg(precio),2) from detalle_orden_despachos where orden_despacho_id = de.id) as precio,

            round(SUM(de.peso_total_neto) * (SELECT ROUND(avg(precio),2) from detalle_orden_despachos where orden_despacho_id = de.id),2) as total_venta,

            (SELECT IFNULL(round(sum(deuda_anterior),2),0) from ventas where cliente_id = de.cliente_id) as saldo,


            ROUND(
                round(SUM(de.peso_total_neto) * (SELECT ROUND(avg(precio),2) from detalle_orden_despachos where orden_despacho_id = de.id),2) +
                (SELECT IFNULL(round(sum(deuda_anterior),2),0) from ventas where cliente_id = de.cliente_id)
            ,2) as total,

            (
                SELECT IFNULL(sum(monto),0) from pagos where venta_id = ven.id and DATE(created_at) = de.fecha_despacho
            ) as monto_pagado,

            round(
                ROUND(
                round(SUM(de.peso_total_neto) * (SELECT ROUND(avg(precio),2) from detalle_orden_despachos where orden_despacho_id = de.id),2) +
                (SELECT IFNULL(round(sum(deuda_anterior),2),0) from ventas where cliente_id = de.cliente_id)
            ,2) -
            (
                SELECT IFNULL(sum(monto),0) from pagos where venta_id = ven.id and DATE(created_at) = de.fecha_despacho
            )
            ,2) as pendiente
            FROM orden_despachos de
            INNER JOIN clientes cli on de.cliente_id = cli.id
            LEFT JOIN ventas ven on de.id = ven.orden_despacho_id
            where de.fecha_despacho = ?
            and de.estado = 1
            group by de.cliente_id;
        ", [$caja->fecha_apertura->format('Y-m-d')]);


        /*d*d(OrdenDespacho::query()
            ->withAggregate('cliente','razon_social')
            ->join('clientes','orden_despachos.cliente_id','clientes.id')
           ->leftJoin('ventas','orden_despachos.id','ventas.orden_despacho_id')
            ->selectRaw("clientes.razon_social as cliente,
            orden_despachos.cantidad_pollos,
            orden_despachos.peso_total_neto,
            round(orden_despachos.peso_total_bruto / orden_despachos.cantidad_pollos,2) as promedio,
            @precio :=(
                SELECT ROUND(avg(precio),2) from detalle_orden_despachos where orden_despacho_id = orden_despachos.id
            ) as precio,
            @totalventa := round(orden_despachos.peso_total_neto * @precio,2) as total_venta,
            @saldo:=(
                SELECT IFNULL(round(sum(deuda_anterior),2),0) from ventas where cliente_id = orden_despachos.cliente_id
            ) as saldo,
            @total :=ROUND(@totalventa + @saldo,2) as total,

            @pagado :=(
                SELECT IFNULL(sum(monto),0) from pagos where venta_id = ventas.id and DATE(created_at) = orden_despachos.fecha_despacho
            ) as monto_pagado,

            round(@total - @pagado,2) as pendiente")
            ->groupBy('cliente_id')
            ->get());*/


    }
}
