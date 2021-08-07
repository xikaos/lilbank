<?php

namespace App\Services\Domain;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Entities\Conta;

class TransferenciaAuditadaService implements TransferenciaServiceContract
{
    public function transferir(Conta $contaOrigem, Conta $contaDestino, $valor) : void
    {

    }
}