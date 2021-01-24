<?php

namespace App\Models;

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

    public function debitar(int $valor) : void
    {
        $novoSaldo = $this->getSaldo() - $valor;

        $this->setSaldo($novoSaldo);
    }

    public function creditar(int $valor) : void
    {
        $novoSaldo = $this->getSaldo() + $valor;

        $this->setSaldo($novoSaldo);
    }
}