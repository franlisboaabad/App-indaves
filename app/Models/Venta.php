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
        'serie_venta',
        'fecha_venta',
        'peso_neto',
        'forma_de_pago',
        'metodo_pago_id',
        'monto_total',
        'url_venta_documento_a4',
        'url_venta_documento_ticket',
        'estado',
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }



    /**
     * Obtener la orden de despacho asociada a la venta.
     */
    public function ordenDespacho()
    {
        return $this->belongsTo(OrdenDespacho::class, 'orden_despacho_id');
    }

    /**
     * Obtener el método de pago asociado a la venta.
     */
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }



}
