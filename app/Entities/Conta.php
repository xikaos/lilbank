<?php

namespace App\Entities;

use App\Entities\Valor;
use App\Exceptions\LimiteInvalidoException;

class Conta
{
    protected $identificador;
    protected $saldo;
    protected $limite;

    public function __construct(
        string $identificador = 'Sem Identificador',
        int $saldo = 0,
        int $limite = 0
    ) {
        $this->identificador = $identificador;
        $this->saldo = $saldo;
        $this->setLimite($limite);
    }

    public function getIdentificador(): string
    {
        return $this->identificador;
    }

    public function getSaldo(): int
    {
        return $this->saldo;
    }

    public function setSaldo(int $valor): void
    {
        $this->saldo = $valor;
    }

    public function getLimite(): int
    {
        return $this->limite;
    }

    public function setLimite(int $valor)
    {
        if ($valor < 0) {
            throw new LimiteInvalidoException();
            return;
        }

        $this->limite = $valor;
    }

    public function debitar(Valor $valor): void
    {
        $novoSaldo = $this->getSaldo() - $valor->getQuantia();

        $this->setSaldo($novoSaldo);
    }

    public function creditar(Valor $valor): void
    {
        $novoSaldo = $this->getSaldo() + $valor->getQuantia();

        $this->setSaldo($novoSaldo);
    }

    public function getSaldoTotalDisponivel()
    {
        return $this->getSaldo() + $this->getLimite();
    }
}
