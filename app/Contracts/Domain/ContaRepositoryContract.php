<?php

namespace App\Contracts\Domain;

use App\Entities\Conta;

interface ContaRepositoryContract
{
    public function getConta(string $identificador): ?Conta;
    public function salvarConta(Conta $conta): void;
}
