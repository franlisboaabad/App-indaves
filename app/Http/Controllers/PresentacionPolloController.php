<?php

namespace App\Http\Controllers;

use App\Models\PresentacionPollo;
use Illuminate\Http\Request;

class PresentacionPolloController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.presentacion-pollo.index')->only('index');
        $this->middleware('can:admin.presentacion-pollo.create')->only('create', 'store');
        $this->middleware('can:admin.presentacion-pollo.edit')->only('edit', 'update');
        $this->middleware('can:admin.presentacion-pollo.show')->only('show');
        $this->middleware('can:admin.presentacion-pollo.destroy')->only('destroy');
    }


    public function index()
    {
        $presentaciones = PresentacionPollo::all();
        return view('admin.presentacion_pollo.index', compact('presentaciones'));
    }


    public function create()
    {
        return view('admin.presentacion_pollo.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            // Agrega otras validaciones según sea necesario
        ]);

        PresentacionPollo::create($request->all());
        return redirect()->route('admin.presentacion_pollo.index');
    }


    public function show(PresentacionPollo $presentacionPollo)
    {
        $presentacion = PresentacionPollo::findOrFail($presentacionPollo);
        return response()->json($presentacion);
    }


    public function edit(PresentacionPollo $presentacionPollo)
    {
        return view('admin.presentacion_pollo.edit', compact('presentacionPollo'));
    }


    public function update(Request $request, PresentacionPollo $presentacionPollo)
    {
        // Validar los datos recibidos
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'tara' => 'required|numeric',
        ]);

        $presentacion = PresentacionPollo::findOrFail($presentacionPollo->id);

        // Actualizar los campos con los datos de la solicitud
        $presentacion->descripcion = $request->input('descripcion');
        $presentacion->tara = $request->input('tara');


        $presentacion->save();
        return response()->json(['success' => true, 'message' => 'Presentación actualizada con éxito.']);
    }


    public function destroy(PresentacionPollo $presentacionPollo)
    {
        $presentacionPollo->delete();
        return redirect()->route('admin.presentacion_pollo.index');
    }
}
