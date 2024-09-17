<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;
    protected $fillable = ['number', 'serie'];


    public const DEFAULT_SERIE_DESPACHO = 2;
    public const DEFAULT_SERIE_VENTA= 1;
}
