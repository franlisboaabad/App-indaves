<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public function getImagenUrlAttribute()
    {
        return Storage::url($this->logo);
    }

}
