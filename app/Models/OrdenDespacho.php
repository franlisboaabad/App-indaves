<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDespacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'serie_orden', 'fecha_despacho','peso_total_bruto','cantidad_jabas','tara','peso_total_neto','estado'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenDespacho::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


}
