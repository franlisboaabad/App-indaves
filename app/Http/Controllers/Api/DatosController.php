<?php

namespace App\Http\Controllers\Api;

use App\Models\Registro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use Illuminate\Support\Facades\Validator;

class DatosController extends Controller
{
    public function guardarDatos(Request $request)
    {

        // Validación
        $validator = Validator::make($request->all(), [
            'numero_identidad' => 'required|string',
            'nombre_apellidos' => 'required|string',
            'celular' => 'required|string',
            'email' => 'required|email',
            'monto' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 significa 2MB
        ]);

        if ($validator->fails()) {
            // Si la validación falla, devuelve un estado HTTP 422 (Entidad No Procesable)
            // junto con los mensajes de error.
            return response()->json(['status' => 'errors', 'message' => $validator->errors()]);
        }

        // Obtener el primer sorteo activo
        $sorteo = Sorteo::where('estado', 1)->first();

        // Verificar si se encontró un sorteo activo
        if (!$sorteo) {
            return response()->json(['status' => 'error', 'message' => 'No hay sorteo activo disponible']);
        }


        try {
            // Guardar los datos en el modelo Registro
            $sorteo = Sorteo::where('estado', 1)->first(); // Obtener el primer sorteo activo

            $registro = Registro::create(['sorteo_id' => $sorteo->id] + $request->all());


            // Guardar la imagen en storage
            if ($request->hasFile('image')) {
                $fotoPago = $request->file('image');
                $path = $fotoPago->store('registros', 'public'); // Almacenar la imagen en storage/app/public/fotos_pago
                $registro->image = $path;
            }

            // Guardar el registro en la base de datos
            $registro->save();

            return response()->json(['status' => 'success', 'message' => 'Su registro fue éxitoso, un representante lo validara y le llegara a su correo electrónico su informacion y ticket.'], 200);
        } catch (\Exception $e) {
            //     // Manejar cualquier error inesperado
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
