<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
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
            'serie_venta' => 'required|string|max:255',
            'fecha_venta' => 'required|date',
            'forma_de_pago' => 'required|in:0,1', // 0: CONTADO ; 1 A CREDITO
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'monto_total' => 'required|numeric',
            'monto_recibido' => 'required|numeric',
        ];
    }
}
