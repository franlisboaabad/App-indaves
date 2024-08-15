<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenDespacho extends Model
{
    use HasFactory;


    protected $fillable = [
        'orden_despacho_id', 'cantidad_pollos', 'peso_bruto', 'cantidad_jabas', 'tara', 'peso_neto','estado'
    ];

    public function ordenDespacho()
    {
        return $this->belongsTo(OrdenDespacho::class);
    }


}
