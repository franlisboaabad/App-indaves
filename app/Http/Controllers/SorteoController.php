<?php

namespace App\Http\Controllers;

use App\Models\Sorteo;
use Illuminate\Http\Request;

class SorteoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.sorteos.index')->only('index');
        $this->middleware('can:admin.sorteos.create')->only('create', 'store');
        $this->middleware('can:admin.sorteos.edit')->only('edit', 'update');
        $this->middleware('can:admin.sorteos.show')->only('show');
        $this->middleware('can:admin.sorteos.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorteos = Sorteo::get();
        return view('admin.sorteos.index', compact('sorteos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sorteos.create');
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
            'nombre_sorteo' => 'required|string',
            'fecha_de_sorteo' => 'required|date',
            'premios' => 'required|string',
            'cantidad_tickets' => 'required',
        ]);

        Sorteo::create($request->all());
        return back()->with('success', 'Sorteo registrado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sorteo  $sorteo
     * @return \Illuminate\Http\Response
     */
    public function show(Sorteo $sorteo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sorteo  $sorteo
     * @return \Illuminate\Http\Response
     */
    public function edit(Sorteo $sorteo)
    {
        return view('admin.sorteos.edit', compact('sorteo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sorteo  $sorteo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sorteo $sorteo)
    {
        $request->validate([
            'nombre_sorteo' => 'required|string',
            'fecha_de_sorteo' => 'required|date',
            'premios' => 'required|string',
            'cantidad_tickets' => 'required',
        ]);

        $sorteo->update($request->all());
        return back()->with('success', 'Sorteo editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sorteo  $sorteo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sorteo $sorteo)
    {
        $sorteo->update(['estado' => 0]);
        return redirect()->route('sorteos.index');
    }
}
