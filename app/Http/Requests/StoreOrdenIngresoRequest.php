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
            'cantidad_jabas' => 'required|integer',
            'cantidad_pollos' => 'required|integer',
            'peso_total' => 'required|numeric',
            'numero_guia' => 'required'
        ];
    }
}
