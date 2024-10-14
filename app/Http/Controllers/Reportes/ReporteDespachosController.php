<?php

namespace App\Http\Controllers\Reportes;

use App\Enums\GlobalStateEnum;
use App\Exports\IngresosExport;
use App\Exports\PurchaseExport;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdenDespacho;
use App\Models\OrdenIngreso;
use App\Models\PresentacionPollo;
use App\Models\TipoPollo;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class ReporteDespachosController extends Controller
{
    public function index()
    {
        $tipo_pollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacion_pollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $clientes = Cliente::query()->select('id', 'razon_social')->get();

        return view('admin.reportes.despachos', compact('tipo_pollos', 'presentacion_pollos', 'clientes'));
    }

    public function search(Request $request)
    {
        return $this->getRecords($request);
    }


    public function export(Request $request, $format)
    {
        $records = $this->getRecords($request);
        $totals = $this->getTotals($request);

        return $format == 'pdf' ? $this->generatePdf($records,$totals) : $this->generateExcel($records,$totals);
    }


    private function generatePdf($records,$totals)
    {
        $empresa = Empresa::query()->firstOrFail();
        $pdf = Pdf::loadView('pdf.reports.despachos.despachos_pdf', [
            'records' => $records,
            'empresa' => $empresa,
            'totals' => $totals
        ]);

        return $pdf->stream();
    }


    private function generateExcel($records,$totals)
    {
        $empresa = Empresa::query()->firstOrFail();

        return (new IngresosExport())
            ->excel_view('pdf.reports.despachos.despachos_pdf')
            ->records($records)
            ->totals($totals)
            ->setCompany($empresa)
            ->download('REPORTE-DESPACHOS' . Carbon::now() . '.xlsx');
    }

    private function getRecords(Request $request)
    {
        $dateInit = $request->input('date_init');
        $dateEnd = $request->input('date_end');
        $cliente_id = $request->input('cliente_id');
        return OrdenDespacho::query()
            ->when($dateInit && $dateEnd, fn(Builder $query) => $query->whereBetween('fecha_despacho', [$dateInit, $dateEnd]))
            ->when($cliente_id, fn(Builder $query) => $query->where('cliente_id', $cliente_id))
            ->withAggregate('cliente', 'razon_social')
            ->where('estado', OrdenDespacho::ESTADO_DESPACHADO)
            ->get()
            ->map(function (OrdenDespacho $ordenIngreso) {
                $ordenIngreso->fecha_despacho = Carbon::parse($ordenIngreso->fecha_despacho)?->format('d-m-Y');
                return $ordenIngreso;
            });
    }

    private function getTotals(Request $request)
    {
        $dateInit = $request->input('date_init');
        $dateEnd = $request->input('date_end');
        $cliente_id = $request->input('cliente_id');

         // Calcular las sumas
         $totals = OrdenDespacho::query()
         ->when($dateInit && $dateEnd, fn(Builder $query) => $query->whereBetween('fecha_despacho', [$dateInit, $dateEnd]))
         ->when($cliente_id, fn(Builder $query) => $query->where('cliente_id', $cliente_id))
         ->where('estado', OrdenDespacho::ESTADO_DESPACHADO)
         ->selectRaw('SUM(cantidad_pollos) as total_cantidad_pollos, SUM(peso_total_bruto) as total_peso_bruto, SUM(cantidad_jabas) as total_cantidad_jabas, SUM(tara) as total_tara, SUM(peso_total_neto) as total_peso_neto')
         ->first();

         return $totals;
    }

}
