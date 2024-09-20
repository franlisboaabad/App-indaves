<?php

namespace App\Http\Controllers;

use App\Enums\GlobalStateEnum;
use Carbon\Carbon;
use App\Models\Caja;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::query()->where('estado', GlobalStateEnum::STATUS_ACTIVE)->get();

        return view('admin.cajas.index', compact('cajas'));
    }

    public function create()
    {
        return view('admin.cajas.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'monto_apertura' => 'required|numeric',
            'fecha_apertura' => 'required|date',
        ]);

        // Puedes añadir aquí cualquier lógica adicional para el usuario
        $userId = auth()->id(); // Obtener el ID del usuario autenticado

        Caja::create([
            'user_id' => $userId,
            'monto_apertura' => $request->monto_apertura,
            'fecha_apertura' => $request->fecha_apertura,
        ]);

        return response()->json(['success' => true]);
    }

    public function show(Caja $caja)
    {
        $caja = Caja::query()
            ->with('pagos',function(HasMany $query){
                $query->with('venta',fn(BelongsTo $q)=> $q->withAggregate('cliente','razon_social'));
            })
            ->findOrFail($caja->id);
        // Asegurarse de que las fechas sean objetos Carbon
        $caja->fecha_apertura = Carbon::parse($caja->fecha_apertura);
        $caja->fecha_cierre = $caja->fecha_cierre ? Carbon::parse($caja->fecha_cierre) : null;

        return view('admin.cajas.show', compact('caja'));
    }

    public function edit(Caja $caja)
    {
        //
    }

    public function update(Request $request, Caja $caja)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'fecha_cierre' => 'nullable|date',
            ]);

            // Buscar la caja por su ID
            $caja = Caja::findOrFail($caja->id);

            // Actualizar los campos necesarios
            $caja->estado_caja = 0;
            $caja->fecha_cierre = $validated['fecha_cierre'] ? Carbon::parse($validated['fecha_cierre']) : null;

            // Guardar los cambios
            $caja->save();

            // Devolver una respuesta JSON
            return response()->json([
                'success' => true,
                'message' => 'Caja actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Loguear el error
            Log::error('Error al actualizar la caja: ' . $e->getMessage());

            // Devolver una respuesta JSON con error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la caja.'
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }


    public function destroy(Caja $caja)
    {
        try {
            // Buscar la caja por su ID
            $caja = Caja::findOrFail($caja->id);

            // Eliminar la caja
            $caja->estado = 0;
            $caja->estado_caja = 0;
            $caja->save();

            // Devolver una respuesta JSON
            return response()->json([
                'success' => true,
                'message' => 'Caja eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Loguear el error
            Log::error('Error al eliminar la caja: ' . $e->getMessage());

            // Devolver una respuesta JSON con error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la caja.'
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }
}
