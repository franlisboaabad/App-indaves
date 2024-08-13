<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.empresas.index')->only('index'); // url internas php.route
        $this->middleware('can:admin.empresas.create')->only('create', 'store');
        $this->middleware('can:admin.empresas.edit')->only('edit', 'update');
        $this->middleware('can:admin.empresas.show')->only('show');
        $this->middleware('can:admin.empresas.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::get();

        return view('admin.empresas.index', compact('empresas'));
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
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validación de archivo
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        try {
            $data = $request->except('logo'); // Obtener todos los datos menos el logo

            // Manejo del archivo de logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = $logoPath;
            }

            // Crear la empresa con los datos
            Empresa::create($data);

            return response()->json(['success' => true, 'message' => 'Empresa creada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la empresa.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        $empresa = Empresa::findOrFail($empresa->id);
        return response()->json($empresa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'required|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048'
        ]);

        $empresa = Empresa::findOrFail($id);
        $empresa->name = $request->name;
        $empresa->address = $request->address;
        $empresa->phone = $request->phone;
        $empresa->email = $request->email;
        $empresa->website = $request->website;
        $empresa->description = $request->description;

        if ($request->hasFile('logo')) {
            $empresa->logo = $request->file('logo')->store('empresas', 'public');
        }

        $empresa->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
