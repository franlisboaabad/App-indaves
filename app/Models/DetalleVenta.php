<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'tipo_pollo_id',
        'presentacion_pollo_id',
        'cantidad_pollos',
        'peso_bruto',
        'cantidad_jabas',
        'tara',
        'peso_neto',
        'precio',
        'subtotal',
        'estado',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function ordenDespacho()
    {
        return $this->belongsTo(OrdenDespacho::class);
    }

    public function tipo_pollo (): BelongsTo
    {
        return $this->belongsTo(TipoPollo::class);
    }

    public function presentacion_pollo (): BelongsTo
    {
        return $this->belongsTo(PresentacionPollo::class);
    }
}
