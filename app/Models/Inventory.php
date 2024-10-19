<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'presentacion_pollo_id',
        'tipo_pollo_id',
        'total_peso',
        'total_pollos',
        'tipo_ingreso'
    ];

    public function tipo_pollo (): BelongsTo
    {
        return $this->belongsTo(TipoPollo::class);
    }

    public function presentacion_pollo (): BelongsTo
    {
        return $this->belongsTo(PresentacionPollo::class);
    }


    public function tipo_ingreso_description()
    {
        if ($this->tipo_ingreso == 1) {
            return "CAMION";
        }

        if($this->tipo_ingreso == 2){
            return "POR STOCK";
        }

        return "MERMAS";
    }

}
