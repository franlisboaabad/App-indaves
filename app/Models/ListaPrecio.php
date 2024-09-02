<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaPrecio extends Model
{
    use HasFactory;

    protected $fillable = [
        'presentacion_pollo_id',
        'tipo_pollo_id',
        'precio',
        'descripcion',
        'estado'
    ];


    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public function tipo_pollo (): BelongsTo
    {
        return $this->belongsTo(TipoPollo::class);
    }

    public function presentacion_pollo (): BelongsTo
    {
        return $this->belongsTo(PresentacionPollo::class);
    }
}
