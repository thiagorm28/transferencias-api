<?php

namespace App\Http\Requests\Transfer;

use App\Exceptions\Transfer\ShopkeeperTransferException;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MakeTransferRequest extends FormRequest
{
    public User $payer;

    protected function prepareForValidation(): void
    {
        // Atribui o usuário autenticado como pagador, ao invés de exigir no request quem está fazendo a transferência
        $this->payer = $this->user();
    }

    public function authorize(): bool
    {
        // Valida se o usuário é comerciante, se for não pode fazer a transferência
        if ($this->payer->isShopkeeper()) {
            throw new ShopkeeperTransferException();
        }

        return true;
    }


    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'decimal:0,2',
                function ($attribute, $value, $fail) { // Valida se o pagador possui o valor da transferência no saldo
                    $user = $this->user();

                    if (!$user->hasEnoughBalance($value)) {
                        $fail('O valor da transferência não pode ser maior que o saldo disponível na carteira.');
                    }
                },
            ],
            'payee' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->payer->id) {
                        $fail('O destinatário da transferência deve ser diferente do remetente.');
                    }
                },
            ]
        ];
    }
}
