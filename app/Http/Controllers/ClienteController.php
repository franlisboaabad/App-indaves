<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.clientes.index')->only('index'); // url internas php.route
        $this->middleware('can:admin.clientes.create')->only('create', 'store');
        $this->middleware('can:admin.clientes.edit')->only('edit', 'update');
        $this->middleware('can:admin.clientes.show')->only('show');
        $this->middleware('can:admin.clientes.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::get();
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'tipo_documento' => 'required|integer',
            'documento' => 'required|string|max:255',
            'nombre_comercial' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255'
        ]);
        // Iniciar una transacción
        DB::beginTransaction();
        try {
            // Verificar si ya existe un cliente con el mismo documento
            $existingCliente = Cliente::where('documento', $validatedData['documento'])->first();
            if ($existingCliente) {
                // Si el cliente ya existe, responder con un error
                return response()->json(['success' => false, 'message' => 'Cliente ya se encuentra registrado'], 400);
            }
            // Crear el cliente
            $cliente = Cliente::create($validatedData);
            // Confirmar la transacción
            DB::commit();
            // Responder con el cliente creado
            return response()->json(['success' => true, 'cliente' => $cliente]);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al crear el cliente.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {

        $validatedData = $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
        ]);
        // Iniciar una transacción
        DB::beginTransaction();
        try {
            // Buscar el cliente por ID
            $cliente = Cliente::find($cliente->id);
            if (!$cliente) {
                return response()->json(['success' => false, 'message' => 'Cliente no encontrado.'], 404);
            }
            // Actualizar el cliente
            $cliente->update($request->all());
            // Confirmar la transacción
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cliente editado correctamente.']);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al editar el cliente.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        // Asegúrate de que el cliente exista
        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado.']);
        }

        // Actualizar el estado del cliente a 0
        $cliente->estado = 0; // Cambia 'estado' por el nombre real de la columna en tu base de datos
        $cliente->save();

        return response()->json(['success' => true, 'message' => 'Cliente desactivado correctamente.']);
    }


    /**
     * Search for a document based on its type.
     */
    public function searchDocument(Request $request)
    {
        $documento = $request->input('documento');
        $tipo_documento = $request->input('tipo_documento');
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImlkZWFzc29mdHBlcnVAZ21haWwuY29tIn0.A2ycg_-A-CeINUKEoUnfG9KEixZzp1RZ-oEOaHxpM5U';

        $response = null;

        if ($tipo_documento == 1) { // DNI
            $response = Http::get("https://dniruc.apisperu.com/api/v1/dni/{$documento}", [
                'token' => $token
            ]);
        } elseif ($tipo_documento == 2) { // RUC
            $response = Http::get("https://dniruc.apisperu.com/api/v1/ruc/{$documento}", [
                'token' => $token
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Tipo de documento no válido.']);
        }

        if ($response->successful()) {
            $data = $response->json();

            if ($tipo_documento == 1) { // DNI
                return response()->json([
                    'success' => true,
                    'data' => [
                        'nombre_comercial' => $data['nombres'] . ' ' . $data['apellidoPaterno'] . ' ' . $data['apellidoMaterno'],
                        'razon_social' => $data['nombres'] . ' ' . $data['apellidoPaterno'] . ' ' . $data['apellidoMaterno'],
                        'direccion' => '',
                        'departamento' => '',
                        'provincia' => '',
                        'distrito' => '',
                    ]
                ]);
            } elseif ($tipo_documento == 2) { // RUC
                return response()->json([
                    'success' => true,
                    'data' => [
                        'nombre_comercial' => $data['nombreComercial'] ?? '',
                        'razon_social' => $data['razonSocial'],
                        'direccion' => $data['direccion'],
                        'departamento' => $data['departamento'],
                        'provincia' => $data['provincia'],
                        'distrito' => $data['distrito'],
                    ]
                ]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Documento no encontrado o error en la API.']);
        }
    }


    //actualizacion para select
    public function getClientes()
    {
        // Recuperar todos los clientes
        $clientes = Cliente::all();

        // Verificar si hay resultados
        if ($clientes->isEmpty()) {
            // Si no hay clientes, devolver una respuesta vacía o con un mensaje adecuado
            return response()->json([], 200);
        }

        // Devolver los clientes en formato JSON
        return response()->json($clientes, 200);
    }
}
