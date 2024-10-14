<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\DetallePedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PedidoController extends Controller
{


    public function __construct()
    {
        $this->middleware('can:admin.pedidos.index')->only('index');
        $this->middleware('can:admin.pedidos.create')->only('create', 'store');
        $this->middleware('can:admin.pedidos.edit')->only('edit', 'update');
        $this->middleware('can:admin.pedidos.show')->only('show');
        $this->middleware('can:admin.pedidos.destroy')->only('destroy');
    }


    public function index()
    {
        $pedidos = Pedido::where('estado', 1)->get();
        return view('admin.pedidos.index', compact('pedidos'));
    }


    public function create()
    {
        $clientes = Cliente::where('estado', 1)->get();
        return view('admin.pedidos.create', compact('clientes'));
    }


    public function store(Request $request)
    {
        try {
            // Validaciones
            $request->validate([
                'fecha_pedido' => 'required|date',
                'total_presa' => 'required|integer',
                'total_brasa' => 'required|integer',
                'detalle' => 'required|array',
                'detalle.*.cliente_id' => 'required|integer', // Valida cliente_id en los detalles
                'detalle.*.cantidad_presa' => 'required|integer',
                'detalle.*.cantidad_brasa' => 'required|integer',
            ]);

            // Crear el pedido
            $pedido = Pedido::create([
                'fecha_pedido' => $request->fecha_pedido,
                'total_presa' => $request->total_presa,
                'total_brasa' => $request->total_brasa,
                'total_tipo' => $request->total_tipo,
            ]);

            // Crear los detalles del pedido
            foreach ($request->detalle as $detalle) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'cliente_id' => $detalle['cliente_id'],
                    'cantidad_presa' => $detalle['cantidad_presa'],
                    'cantidad_brasa' => $detalle['cantidad_brasa'],
                ]);
            }

            return response()->json(['success' => true, 'pedido_id' => $pedido->id], 201);

        } catch (ValidationException $e) {
            // Manejo de errores de validación
            return response()->json(['message' => 'Errores de validación', 'errors' => $e->validator->errors()], 422);
        } catch (QueryException $e) {
            // Manejo de errores de base de datos
            return response()->json(['message' => 'Error al guardar el pedido'], 500);
        } catch (\Exception $e) {
            // Manejo de errores inesperados
            return response()->json(['message' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }



    public function show(Pedido $pedido)
    {
        //
    }


    public function edit(Pedido $pedido)
    {

        $pedido = Pedido::find($pedido->id);
        $detalles = $pedido->detalles; // Obtiene todos los detalles del pedido
        $clientes = Cliente::where('estado', 1)->get();

        return view('admin.pedidos.edit', compact('pedido', 'detalles', 'clientes'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        try {
            // Valida los datos recibidos
            $request->validate([
                'fecha_pedido' => 'required|date',
                'detalle' => 'required|array',
                'detalle.*.cliente_id' => 'nullable|integer',
                'detalle.*.cantidad_presa' => 'required|integer',
                'detalle.*.cantidad_brasa' => 'required|integer',
            ]);

            // Busca el pedido a editar
            $pedido = Pedido::findOrFail($pedido->id);

            // Actualiza la fecha del pedido
            $pedido->fecha_pedido = $request->fecha_pedido;

            // Inicializa los totales y un arreglo para verificar cliente_id únicos
            $totalPresa = 0;
            $totalBrasa = 0;
            $clienteIds = [];

            // Agrega nuevos detalles del pedido
            foreach ($request->detalle as $detalleData) {
                // Verifica si cliente_id es válido
                if (empty($detalleData['cliente_id'])) {
                    continue; // Ignora si no hay cliente_id
                }

                // Verifica si el cliente_id ya fue agregado
                if (in_array($detalleData['cliente_id'], $clienteIds)) {
                    return response()->json(['message' => 'El cliente ID ' . $detalleData['cliente_id'] . ' ya fue agregado.'], 400);
                }

                // Agrega el cliente_id al arreglo
                $clienteIds[] = $detalleData['cliente_id'];

                // Crea un nuevo detalle
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'cliente_id' => $detalleData['cliente_id'],
                    'cantidad_presa' => $detalleData['cantidad_presa'],
                    'cantidad_brasa' => $detalleData['cantidad_brasa'],
                ]);

                // Suma las cantidades a los totales
                $totalPresa += $detalleData['cantidad_presa'];
                $totalBrasa += $detalleData['cantidad_brasa'];
            }

            // Actualiza los totales del pedido
            $pedido->total_presa += $totalPresa;
            $pedido->total_brasa += $totalBrasa;
            // Total tipo puede ser calculado si es necesario
            $pedido->total_tipo = 0; // Ajusta esto según tu lógica

            // Guarda los cambios en el pedido
            $pedido->save();

            return response()->json(['message' => 'Pedido actualizado con éxito.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error al actualizar el pedido.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocurrió un error inesperado.'], 500);
        }
    }

    public function destroy(Pedido $pedido)
    {
        //
    }

    private function generatePdf_A4($id)
    {
        try {
            // Obtener la empresa
            $empresa = Empresa::query()->firstOrFail();

            // Buscar el pedido
            $pedido = Pedido::findOrFail($id); // Usa findOrFail para validar existencia

            // Obtener detalles del pedido
            $detalles = $pedido->detalles; // Obtiene todos los detalles del pedido

            // Cargar la vista del PDF
            $pdf = Pdf::loadView('pdf.pedidos.pedido_a4', [
                'pedido' => $pedido,
                'empresa' => $empresa,
                'detalles' => $detalles, // Asegúrate de pasar los detalles
            ]);

            return $pdf->stream();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar el PDF.'], 500);
        }
    }

    public function print($pedido, $format)
    {
        return strtolower($format) == 'ticket' ? $this->generateTicket($pedido) : $this->generatePdf_A4($pedido);
    }
}
