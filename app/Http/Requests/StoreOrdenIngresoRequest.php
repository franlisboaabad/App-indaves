<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenIngresoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'peso_bruto' => 'required|integer',
            'peso_tara' => 'required|integer',
            'peso_neto' => 'required|numeric',
            'numero_guia' => 'required'
        ];
    }
}
