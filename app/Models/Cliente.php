<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_documento', 'documento', 'nombre_comercial', 'razon_social', 'direccion','departamento', 'provincia','distrito','email','celular', 'estado'];

    //relaciones

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenDeServicio::class);
    }

    public function saldos(): HasMany
    {
        return $this->hasMany(Saldo::class);
    }
    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

}
