<?php

namespace App\Http\Controllers;

use App\Models\ListaPrecio;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $precios = ListaPrecio::where('estado', 1)->get();


        return view('admin.precios.index', compact('precios'));
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
        $validatedData = $request->validate([
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        // Lógica para guardar el nuevo precio
        ListaPrecio::create($validatedData);

        return response()->json(['message' => 'El precio ha sido agregado exitosamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListaPrecio  $listaPrecio
     * @return \Illuminate\Http\Response
     */
    public function show(ListaPrecio $listaPrecio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ListaPrecio  $listaPrecio
     * @return \Illuminate\Http\Response
     */
    public function edit(ListaPrecio $listaPrecio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ListaPrecio  $listaPrecio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListaPrecio $listaPrecio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListaPrecio  $listaPrecio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListaPrecio $listaPrecio)
    {

        $precio = ListaPrecio::findOrFail($listaPrecio->id);
        $precio->estado = false; // O $precio->estado = 0;
        $precio->save();

        return redirect()->back()->with('success', 'El precio ha sido eliminado correctamente.');
    }
}
