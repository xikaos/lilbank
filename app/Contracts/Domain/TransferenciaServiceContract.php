<?php


namespace App\Contracts\Domain;

use App\Models\Conta;
use App\Models\Valor;

interface TransferenciaServiceContract
{
    function transferir(Conta $contaOrigem, Conta $contaDestino, Valor $valor) : void;
}