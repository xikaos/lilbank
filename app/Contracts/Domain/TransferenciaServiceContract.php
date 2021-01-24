<?php


namespace App\Contracts\Domain;

use App\Models\Conta;

interface TransferenciaServiceContract {
    function transferir(Conta $contaOrigem, Conta $contaDestino, int $valor) : void;
}