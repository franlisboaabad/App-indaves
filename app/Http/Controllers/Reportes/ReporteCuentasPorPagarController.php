<?php

namespace App\Http\Controllers\Reportes;

use App\Enums\GlobalStateEnum;
use App\Exports\IngresosExport;
use App\Exports\PurchaseExport;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdenDespacho;
use App\Models\PresentacionPollo;
use App\Models\TipoPollo;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReporteCuentasPorPagarController extends Controller
{
    public function index()
    {
        $tipo_pollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacion_pollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $clientes = Cliente::query()->select('id', 'razon_social')->get();

        return view('admin.reportes.cuentas-por-cobrar', compact('tipo_pollos', 'presentacion_pollos', 'clientes'));
    }

    public function search(Request $request)
    {
        return $this->getRecords($request);
    }


    public function export(Request $request, $format)
    {
        $records = $this->getRecords($request);

        return $format == 'pdf' ? $this->generatePdf($records) : $this->generateExcel($records);
    }


    private function generatePdf($records)
    {
        $empresa = Empresa::query()->firstOrFail();
        $pdf = Pdf::loadView('pdf.reports.cuentas-por-cobrar.cuentas-por-cobrar_pdf', [
            'records' => $records,
            'empresa' => $empresa
        ]);

        return $pdf->stream();
    }


    private function generateExcel($records)
    {
        $empresa = Empresa::query()->firstOrFail();

        return (new IngresosExport())
            ->excel_view('pdf.reports.cuentas-por-cobrar.cuentas-por-cobrar_pdf')
            ->records($records)
            ->setCompany($empresa)
            ->download('REPORTE-CUENTAS-POR-COBRAR' . Carbon::now() . '.xlsx');
    }

    private function getRecords(Request $request)
    {
        $dateInit = $request->input('date_init');
        $dateEnd = $request->input('date_end');
        $cliente_id = $request->input('cliente_id');

        return Venta::query()
            ->when($dateInit && $dateEnd, fn(Builder $query) => $query->whereBetween('fecha_venta', [$dateInit, $dateEnd]))
            ->when($cliente_id , fn(Builder $query) => $query->where('cliente_id',$cliente_id))
            ->withAggregate('cliente', 'razon_social')
            ->where('monto_pendiente','>',0)
            ->get()
            ->map(function (Venta $venta) {
                $venta->fecha_venta = Carbon::parse($venta->fecha_venta)?->format('d-m-Y');
                return $venta;
            });
    }
}
