<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'monto_apertura', 'fecha_apertura', 'fecha_cierre', 'estado_caja', 'monto_cierre','estado'];

    protected $casts = [
        'fecha_apertura' => 'date'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    // Relación con Pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
