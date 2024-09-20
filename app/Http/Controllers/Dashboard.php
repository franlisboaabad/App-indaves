<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sorteo;
use App\Models\Registro;
use Illuminate\Http\Request;
use App\Models\DetalleTicket;
use App\Models\OrdenDespacho;
use App\Models\OrdenIngreso;

class Dashboard extends Controller
{
    //

    public function home()
    {
        $countUser = User::count();
        $countOIngreso = OrdenIngreso::count();
        $countODespacho = OrdenDespacho::count();

        return view('dashboard', compact('countUser','countOIngreso', 'countODespacho'));
    }
}
