<?php

namespace App\Services\Domain;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Models\Conta;
use App\Models\Valor;

class TransferenciaService implements TransferenciaServiceContract {
    public function transferir(Conta $contaOrigem, Conta $contaDestino, Valor $valor) : void
    {
        $contaOrigem->debitar($valor);
        $contaDestino->creditar($valor);
    }
}