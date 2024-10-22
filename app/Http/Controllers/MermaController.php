<?php

namespace App\Http\Controllers;

use App\Models\Merma;
use App\Models\Inventory;
use Exception;
use Illuminate\Http\Request;
use App\Models\DetalleMermas;
use Illuminate\Support\Facades\DB;

class MermaController extends Controller
{

    public function index()
    {
        $mermas = Merma::latest()->get();
        return view('admin.mermas.index', compact('mermas'));
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

        DB::beginTransaction();

        try {

            // Crear una nueva Merma
            $merma = Merma::query()->create([
                'total_peso' => $request->total_peso,
            ]);

            // Registrar los detalles de la merma
            foreach ($request->detalles as $detalle) {
                $merma->detalles()->create($detalle);
            }

            Inventory::query()->update(['total_peso' => 0]);

            DB::commit();

            \App\Models\Peso::truncate();

            // Si todo va bien, devolver la respuesta
            return response()->json(['message' => 'Merma registrada correctamente.']);
        }
        catch(Exception $exception){
            DB::rollBack();

            return response()->json(['message' => 'Ocurrió un error al registrar la merma.'], 404);
        }

    }


    public function show(Merma $merma)
    {
        return view('admin.mermas.show',compact('merma'));
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
