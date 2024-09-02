<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListaPrecioRequest;
use App\Models\ListaPrecio;
use App\Models\PresentacionPollo;
use App\Models\TipoPollo;
use Illuminate\Http\Request;

class ListaPrecioController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.precios.index')->only('index');
        $this->middleware('can:admin.precios.create')->only('create', 'store');
        $this->middleware('can:admin.precios.edit')->only('edit', 'update');
        $this->middleware('can:admin.precios.show')->only('show');
        $this->middleware('can:admin.precios.destroy')->only('destroy');
    }

    public function index()
    {
        $precios = ListaPrecio::query()
            ->withAggregate('tipo_pollo','descripcion')
            ->withAggregate('presentacion_pollo','descripcion')
            ->where('estado', ListaPrecio::STATUS_ACTIVE)
            ->get();

        $tipo_pollos = TipoPollo::query()->get();
        $presentacion_pollos = PresentacionPollo::query()->get();

        return view('admin.precios.index', compact('precios','tipo_pollos','presentacion_pollos'));
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


    public function store(StoreListaPrecioRequest $request)
    {
        ListaPrecio::query()->create($request->validated());

        return response()->json(['message' => 'El precio ha sido agregado exitosamente.']);
    }


    public function show(ListaPrecio $listaPrecio)
    {
        //
    }

    public function edit(ListaPrecio $listaPrecio)
    {
        //
    }

    public function update(StoreListaPrecioRequest $request, ListaPrecio $listaPrecio)
    {
        try {
            $precio = ListaPrecio::findOrFail($listaPrecio->id);
            $precio->precio = $request->input('precio');
            $precio->descripcion = $request->input('descripcion');
            $precio->save();

            // Respuesta JSON para AJAX
            return response()->json(['success' => true, 'message' => 'Precio actualizado correctamente.']);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['success' => false, 'message' => 'Error al actualizar el precio.'], 500);
        }
    }

    public function destroy(ListaPrecio $listaPrecio)
    {
        $listaPrecio->estado = ListaPrecio::STATUS_INACTIVE; // O $precio->estado = 0;
        $listaPrecio->save();

        return redirect()->back()->with('success', 'El precio ha sido eliminado correctamente.');
    }
}
