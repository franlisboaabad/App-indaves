<?php

namespace App\Http\Controllers;

use App\Models\DetalleTicket;
use App\Models\Registro;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    //

    public function home()
    {
        $totalSorteo = Sorteo::count();
        $totalRegistros = Registro::where('estado', 1)->count();
        $totalTickets = DetalleTicket::count();

        return view('dashboard', compact('totalSorteo','totalRegistros','totalTickets'));
    }
}
