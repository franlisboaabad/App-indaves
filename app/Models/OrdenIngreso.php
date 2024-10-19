<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenIngreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_ingreso',
        'numero_guia',
        'peso_bruto',
        'peso_tara',
        'peso_neto',
        'estado',
        'tipo_ingreso'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalle(): HasMany
    {
        return $this->hasMany(DetalleOrdenIngreso::class);
    }
}
