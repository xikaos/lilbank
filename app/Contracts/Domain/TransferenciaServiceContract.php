<?php


namespace App\Contracts\Domain;

use App\Entities\Conta;
use App\Entities\Valor;

interface TransferenciaServiceContract
{
    function transferir(Conta $contaOrigem, Conta $contaDestino, Valor $valor) : void;
}