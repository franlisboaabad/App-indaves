<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenIngreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_guia',
        'peso_bruto',
        'peso_tara',
        'peso_neto',
        'estado'
    ];


    public function detalle()
    {
        return $this->hasMany(DetalleOrdenIngreso::class);
    }
}
