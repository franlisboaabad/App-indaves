<?php

namespace App\Http\Controllers\Reportes;

use App\Enums\GlobalStateEnum;
use App\Exports\IngresosExport;
use App\Exports\PurchaseExport;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\OrdenIngreso;
use App\Models\PresentacionPollo;
use App\Models\TipoPollo;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ReporteIngresosController extends Controller
{
    public function index()
    {
        $tipo_pollos = TipoPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();
        $presentacion_pollos = PresentacionPollo::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.reportes.ingresos', compact('tipo_pollos', 'presentacion_pollos'));
    }

    public function search(Request $request)
    {
        return $this->getRecords($request);
    }


    public function export(Request $request,$format)
    {
        $records = $this->getRecords($request);

        return $format == 'pdf' ? $this->generatePdf($records) : $this->generateExcel($records);
    }


    private function generatePdf($records)
    {
        $empresa = Empresa::query()->firstOrFail();
        $pdf = Pdf::loadView('pdf.reports.ingresos.ingresos_pdf',[
            'records' => $records,
            'empresa' => $empresa
        ]);

        return $pdf->stream();
    }


    private function generateExcel($records)
    {
        $empresa = Empresa::query()->firstOrFail();

        return (new IngresosExport())
            ->excel_view('pdf.reports.ingresos.ingresos_pdf')
            ->records($records)
            ->setCompany($empresa)
            ->download('REPORTE-INGRESOS'.Carbon::now().'.xlsx');
    }
    private function getRecords(Request $request)
    {
        $dateInit = $request->input('date_init');
        $dateEnd = $request->input('date_end');
        return OrdenIngreso::query()
            ->when($dateInit && $dateEnd, fn(Builder $query) => $query->whereBetween('fecha_ingreso', [$dateInit, $dateEnd]))
            ->withSum('detalle', 'cantidad_pollos')
            ->withAggregate('user', 'name')
            ->get()
            ->map(function (OrdenIngreso $ordenIngreso) {
                $ordenIngreso->fecha_ingreso = Carbon::parse($ordenIngreso->fecha_ingreso)?->format('d-m-Y');
                return $ordenIngreso;
            });
    }
}
