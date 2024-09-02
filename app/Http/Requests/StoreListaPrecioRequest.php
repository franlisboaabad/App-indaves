<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListaPrecioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tipo_pollo_id' => 'required',
            'presentacion_pollo_id' => 'required',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ];
    }
}
