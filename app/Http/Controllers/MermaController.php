<?php

namespace App\Http\Controllers;

use App\Models\DetalleMermas;
use App\Models\Merma;
use App\Models\Inventory;
use Illuminate\Http\Request;

class MermaController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'total_peso' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.presentacion' => 'required|string',
            'detalles.*.tipo' => 'required|string',
            'detalles.*.peso' => 'required|numeric',
        ]);

        // Crear una nueva Merma
        $merma = Merma::create([
            'total_peso' => $request->total_peso,
        ]);

        // Registrar los detalles de la merma
        foreach ($request->detalles as $detalle) {
            DetalleMermas::create([
                'merma_id' => $merma->id,
                'presentacion' => $detalle['presentacion'],
                'tipo' => $detalle['tipo'],
                'peso' => $detalle['peso'],
            ]);
        }

        // Actualizar el inventario
        Inventory::query()->update(['total_peso' => 0]);

        // Limpiar el modelo Peso
        // Asegúrate de tener el modelo Peso configurado
        \App\Models\Peso::truncate();

        return response()->json(['message' => 'Merma registrada correctamente.']);
    }


    public function show(Merma $merma)
    {
        //
    }


    public function edit(Merma $merma)
    {
        //
    }


    public function update(Request $request, Merma $merma)
    {
        //
    }


    public function destroy(Merma $merma)
    {
        //
    }
}
