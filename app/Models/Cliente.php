<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_documento', 'documento', 'nombre_comercial', 'razon_social', 'direccion','departamento', 'provincia','distrito','email','celular', 'estado'];

    //relaciones

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function ordenes()
    {
        return $this->hasMany(OrdenDeServicio::class);
    }


    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

}
