<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion'];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'metodo_pago_id');
    }


    // Relación con Pago
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

}
