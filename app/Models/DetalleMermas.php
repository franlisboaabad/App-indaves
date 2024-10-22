<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleMermas extends Model
{
    use HasFactory;

    protected $fillable = ['merma_id','presentacion','tipo','peso','tipo_ingreso'];



}
