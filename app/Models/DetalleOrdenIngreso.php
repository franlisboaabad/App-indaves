<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleOrdenIngreso extends Model
{
    use HasFactory;


    protected $fillable = [
        'orden_ingreso_id',
        'tipo_pollo_id',
        'presentacion_pollo_id',
        'cantidad_pollos',
        'peso_bruto',
        'cantidad_jabas',
        'tara',
        'peso_neto',
        'precio',
        'subtotal',
        'estado'
    ];

    public function orden_ingreso()
    {
        return $this->belongsTo(OrdenIngreso::class);
    }

    public function tipo_pollo(): BelongsTo
    {
        return $this->belongsTo(TipoPollo::class);
    }

    public function presentacion_pollo(): BelongsTo
    {
        return $this->belongsTo(PresentacionPollo::class);
    }


}
