<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;


    protected $fillable = ['sorteo_id', 'numero_identidad', 'nombre_apellidos', 'celular', 'email', 'monto', 'image', 'estado_registro', 'estado'];


    public function getGetImagenAttribute()
    {
        if ($this->image) {
            return url("storage/$this->image");
        }
    }


    public function sorteo()
    {
        return $this->belongsTo(Sorteo::class);
    }

     // Relación uno a muchos con Ticket
     public function tickets()
     {
         return $this->hasMany(Ticket::class);
     }


}
