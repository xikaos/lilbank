<?php

namespace App\Services\Domain;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Models\Conta;

class TransferenciaService implements TransferenciaServiceContract {
    public function transferir(Conta $contaOrigem, Conta $contaDestino, int $valor) : void
    {
        $contaOrigem->debitar($valor);
        $contaDestino->creditar($valor);
    }
}