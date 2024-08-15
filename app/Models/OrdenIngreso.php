<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenIngreso extends Model
{
    use HasFactory;

    protected $fillable = ['numero_guia', 'cantidad_jabas', 'cantidad_pollos', 'peso_total', 'estado'];
}