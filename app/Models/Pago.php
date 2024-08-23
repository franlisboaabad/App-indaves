<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['caja_id', 'metodo_pago_id','monto'];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }


    // Relación con MetodoPago
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }

}
