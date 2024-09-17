<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDespacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'serie_orden',
        'fecha_despacho',
        'cantidad_pollos',
        'peso_total_bruto',
        'cantidad_jabas',
        'tara',
        'peso_total_neto',
        'subtotal',
        'url_orden_documento_a4',
        'url_orden_documento_ticket',
        'estado_despacho',
        'estado'
    ];

    public const ESTADO_DESPACHADO = 1;
    public const ESTADO_INACTIVE = 0;
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenDespacho::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'orden_despacho_id');
    }


    public function tipoPollo()
    {
        return $this->belongsTo(TipoPollo::class, 'tipo_pollo_id');
    }


}
