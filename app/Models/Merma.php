<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merma extends Model
{
    use HasFactory;

    protected $fillable = ['total_peso','estado'];

    public function detalles()
    {
        return $this->hasMany(DetalleMermas::class,'merma_id');
    }

}
