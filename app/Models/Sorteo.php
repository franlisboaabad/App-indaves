<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorteo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_sorteo', 'fecha_de_sorteo', 'premios', 'descripcion_del_sorteo', 'cantidad_tickets','cantidad_vendida','opciones','estado'];


    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
}
