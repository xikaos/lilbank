<?php

namespace App\Contracts\Application;

use App\Models\Conta;

interface ContaRepositoryContract {
    function getConta(string $identificador) : ?Conta;
    function salvarConta(Conta $conta) : void;
}