<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPollo extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'estado'];

    public  const POLLO_BENEFICIADO_ID = 2;

    public function ordenes()
    {
        return $this->hasMany(OrdenDespacho::class, 'tipo_pollo_id');
    }
}
