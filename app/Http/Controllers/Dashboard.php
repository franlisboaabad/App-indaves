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
        return view('dashboard');
    }
}
