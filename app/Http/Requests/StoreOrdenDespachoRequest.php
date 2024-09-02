<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenDespachoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'cliente_id' => 'required|integer',
            'serie_orden' => 'required|string|max:255',
            'fecha_despacho' => 'required|date',
            'peso_total_bruto' => 'required|numeric',
            'cantidad_jabas' => 'required|integer',
            'tara' => 'required|numeric',
            'peso_total_neto' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.cantidad_pollos' => 'required|numeric',
            'detalles.*.peso_bruto' => 'required|numeric',
            'detalles.*.cantidad_jabas' => 'required|integer',
            'detalles.*.tara' => 'required|numeric',
            'detalles.*.peso_neto' => 'required|numeric',
        ];
    }
}
