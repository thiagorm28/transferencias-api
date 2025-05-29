<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
        'cpf_cnpj',
        'wallet',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet' => 'float',
        ];
    }

    public function transfersSent(): HasMany
    {
        return $this->hasMany(Transfer::class, 'payer_id');
    }

    public function transfersReceived(): HasMany
    {
        return $this->hasMany(Transfer::class, 'payee_id');
    }

    public function debit(float $value): void
    {
        $this->wallet -= $value;
        $this->save();
    }

    public function credit(float $value): void
    {
        $this->wallet += $value;
        $this->save();
    }
}
