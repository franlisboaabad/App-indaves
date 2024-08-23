<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cajas = Caja::all();
        return view('admin.cajas.index', compact('cajas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cajas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        $caja = Caja::with('pagos')->findOrFail($caja->id);

        // Asegurarse de que las fechas sean objetos Carbon
        $caja->fecha_apertura = Carbon::parse($caja->fecha_apertura);
        $caja->fecha_cierre = $caja->fecha_cierre ? Carbon::parse($caja->fecha_cierre) : null;

        return view('admin.cajas.show', compact('caja'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function edit(Caja $caja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        try {
            // Buscar la caja por su ID
            $caja = Caja::findOrFail($caja->id);

            // Eliminar la caja
            $caja->delete();

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
