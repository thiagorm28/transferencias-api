<?php

namespace App\Repositories\Transfer;

interface IExternalAuthRepository
{
    public function authorize(): bool;
}
