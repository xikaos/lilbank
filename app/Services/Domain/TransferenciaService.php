<?php

namespace App\Services\Domain;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Entities\Conta;
use App\Entities\Valor;

use App\Exceptions\LimiteInsuficienteException;

class TransferenciaService implements TransferenciaServiceContract
{
    public function transferir(Conta $contaOrigem, Conta $contaDestino, Valor $valor) : void
    {
        $this->validaTransferencia($contaOrigem, $valor);
        $contaOrigem->debitar($valor);
        $contaDestino->creditar($valor);
    }

    private function validaTransferencia(Conta $contaOrigem, Valor $valor): void 
    {
        $saldoTotalDisponivel = $contaOrigem->getSaldoTotalDisponivel();
        $diferenca = $saldoTotalDisponivel - $valor->getQuantia();

        if ($diferenca < 0) {
            throw new LimiteInsuficienteException();
            return;
        }

    }
}