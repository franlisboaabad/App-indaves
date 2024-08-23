<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'orden_despacho_id',
        'cantidad_pollos',
        'peso_bruto',
        'cantidad_jabas',
        'tara',
        'peso_neto',
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
}
