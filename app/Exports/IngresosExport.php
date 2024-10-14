<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class IngresosExport extends BaseExport implements FromView
{

    use Exportable;

    public function view(): \Illuminate\Contracts\View\View
    {
        return view($this->excel_view, [
            'records' => $this->records,
            'empresa' => $this->company,
            'totals' => $this->totals
        ]);
    }
}
