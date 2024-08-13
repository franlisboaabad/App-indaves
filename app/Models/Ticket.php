<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['registro_id', 'cantidad_tickets','ticketpdf'];

    // Relación muchos a uno con Registro
    public function registro()
    {
        return $this->belongsTo(Registro::class);
    }

    // Relación uno a muchos con DetalleTicket
    public function detalles()
    {
        return $this->hasMany(DetalleTicket::class);
    }



}
