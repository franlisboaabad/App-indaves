<?php

namespace App\Exports;

use App\Models\Empresa;

class BaseExport
{


    public string $header_style = 'background: forestgreen; color: white; font-weight: bold; width: 90px; height: 22px;';

    public string $sub_header_style = 'font-family:Courier New; background: #d9d9d9; color: black; font-weight: bold; width: 90px; height: 22px;';

    public function excel_view($excel_view): self
    {
        $this->excel_view = $excel_view;

        return $this;
    }


    public function setCompany($company): self
    {
        $this->company = $company;

        return $this;
    }

    public function records($records): self
    {
        $this->records = $records;

        return $this;
    }


    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
