<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class MakeTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'min:0.01',
                'decimal:0,2',
            ],
            'payee' => [
                'required',
                'exists:users,id',
            ],
        ];
    }
}
