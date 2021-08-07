<?php

namespace App\Contracts\Application;

use App\Entities\Conta;

interface ContaRepositoryContract
{
    function getConta(string $identificador) : ?Conta;
    function salvarConta(Conta $conta) : void;
}