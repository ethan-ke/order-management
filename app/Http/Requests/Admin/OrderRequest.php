<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone'       => 'required|string',
            'price'       => 'required|numeric',
            'deduction'   => 'required|numeric',
            'status'      => 'required|integer',
            'room_number' => 'required|string',
        ];
    }
}
