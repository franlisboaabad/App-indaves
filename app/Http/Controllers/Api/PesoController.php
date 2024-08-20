<?php

namespace App\Http\Controllers\Api;

use App\Models\Peso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PesoController extends Controller
{
    public function guardarPeso(Request $request)
    {
        // Validación del peso
        $request->validate([
            'peso' => 'required|numeric',
        ]);

        // Obtener el valor del peso del request
        $peso = $request->input('peso');

        // Guardar el peso en la base de datos (se asume que Peso es un modelo Eloquent)
        Peso::create(['peso' => $peso]);

        // Devolver una respuesta JSON
        return response()->json(['success' => true]);
    }

    // Nuevo método para obtener el último peso registrado
    public function obtenerPeso()
    {
        $peso = Peso::latest()->first(); // Obtiene el último peso registrado

        return response()->json(['peso' => $peso ? $peso->peso : null]);
    }

}
