<?php

namespace App\Contracts\Domain;

use Ramsey\Uuid\Uuid;

use App\Models\Conta;

interface ContaRepositoryContract {
    function getConta(string $identificador) : ?Conta;
    function salvarConta(Conta $conta) : void;
}