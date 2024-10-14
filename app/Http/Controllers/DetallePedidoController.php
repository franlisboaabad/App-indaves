<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\DetallePedido;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function show(DetallePedido $detallePedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function edit(DetallePedido $detallePedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetallePedido $detallePedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function destroy($pedidoId, $clienteId)
    {
        try {
            // Busca el detalle correspondiente al pedido y al cliente
            $detalle = DetallePedido::where('pedido_id', $pedidoId)
                ->where('cliente_id', $clienteId)
                ->firstOrFail(); // Usar firstOrFail para lanzar una excepción si no se encuentra

            // Obtén el pedido correspondiente
            $pedido = Pedido::findOrFail($pedidoId); // Lanzará excepción si no se encuentra

            // Descuenta los totales del pedido
            $pedido->total_presa -= $detalle->cantidad_presa;
            $pedido->total_brasa -= $detalle->cantidad_brasa;
            $pedido->total_tipo -= $detalle->cantidad_tipo;

            // Asegúrate de que los totales no sean negativos
            $pedido->total_presa = max(0, $pedido->total_presa);
            $pedido->total_brasa = max(0, $pedido->total_brasa);
            $pedido->total_tipo = max(0, $pedido->total_tipo);

            // Guarda los cambios en el pedido
            $pedido->save();

            // Elimina el detalle
            $detalle->delete();

            return response()->json(['message' => 'El detalle ha sido eliminado y los totales actualizados.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Detalle o pedido no encontrado.'], 404);
        } catch (QueryException $e) {
            // Manejo de errores de consulta, por ejemplo, si falla el guardado
            return response()->json(['message' => 'Error al eliminar o actualizar el pedido.'], 500);
        } catch (\Exception $e) {
            // Manejo de cualquier otra excepción
            return response()->json(['message' => 'Ocurrió un error inesperado.'], 500);
        }
    }
}
