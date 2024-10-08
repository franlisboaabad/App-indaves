<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Los campos que pueden ser asignados en masa
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'status',
    ];

}
