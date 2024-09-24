<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Los atributos que se pueden asignar masivamente.
    protected $fillable = [
        'orden_despacho_id',
        'cliente_id',
        'serie_venta',
        'fecha_venta',
        'peso_neto',
        'forma_de_pago',
        'monto_total',
        'monto_recibido',
        'monto_pendiente',
        'deuda_anterior',
        'comentario'.
        'saldo',
        'estado'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Obtener la orden de despacho asociada a la venta.
     */
    public function ordenDespacho()
    {
        return $this->belongsTo(OrdenDespacho::class, 'orden_despacho_id');
    }
}
