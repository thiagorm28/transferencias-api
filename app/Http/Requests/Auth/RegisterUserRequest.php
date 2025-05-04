<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf_cnpj' => 'required|unique:users',
            'password' => 'required|confirmed|min:6',
            'type' => ['required', Rule::in([UserType::COMMON->value, UserType::SHOPKEEPER->value])],
        ];
    }
}
