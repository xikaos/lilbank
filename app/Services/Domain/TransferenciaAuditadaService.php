<?php

namespace App\Services\Domain;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Models\Conta;

class TransferenciaAuditadaService implements TransferenciaServiceContract {
    public function transferir(Conta $contaOrigem, Conta $contaDestino, $valor) : void
    {

    }
}