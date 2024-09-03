<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion'];

    public const METODO_PAGO_SALDO = 6;

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'metodo_pago_id');
    }

    public function ordenDespacho()
    {
        return $this->belongsTo(OrdenDespacho::class, 'orden_despacho_id');
    }

    // Relación con Pago
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

}
