<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = ['fecha_pedido','total_presa','total_brasa','total_tipo','estado']; // Campos que se pueden llenar


    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

}
