<?php

namespace App\Models;

use App\Models\Valor;

class Conta
{
    protected $saldo;

    public function __construct(int $saldo = 0)
    {
        $this->setSaldo($saldo);
    }

    public function getSaldo() : int
    {
        return $this->saldo;
    }

    public function setSaldo(int $valor) : void
    {
        $this->saldo = $valor;
    }

    public function debitar(Valor $valor) : void
    {
        $novoSaldo = $this->getSaldo() - $valor->getQuantia();

        $this->setSaldo($novoSaldo);
    }

    public function creditar(Valor $valor) : void
    {
        $novoSaldo = $this->getSaldo() + $valor->getQuantia();

        $this->setSaldo($novoSaldo);
    }
}