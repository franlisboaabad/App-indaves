<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTicket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'correlativo_ticket'];


    // Relación muchos a uno con Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


}
