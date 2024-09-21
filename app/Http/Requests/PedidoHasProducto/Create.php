<?php

namespace App\Http\Requests\PedidoHasProducto;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'pesos' => 'required|array',
            'pesos.producto' => 'integer',
            'pesos.precio' => 'numeric',
            'pesos.cantidad' => 'numeric',
            'pedido' => 'required|integer',

        ];
    }
}
