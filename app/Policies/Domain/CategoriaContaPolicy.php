<?php

namespace App\Policies\Domain;

use App\Enums\Domain\CategoriaContaEnum;
use App\Entities\Conta;

class CategoriaContaPolicy
{
    public const SALDO_CONTA_CATEGORIA_A = 1500;

    public function categoriza(Conta $conta): string
    {
        if ($this->isContaClasseA($conta)) {
            return CategoriaContaEnum::CATEGORIA_A;
        }

        if ($this->isContaClasseB($conta)) {
            return CategoriaContaEnum::CATEGORIA_B;
        }
    }

    public function isContaClasseA(Conta $conta)
    {
        return $conta->getSaldoTotalDisponivel() >= self::SALDO_CONTA_CATEGORIA_A;
    }

    public function isContaClasseB(Conta $conta)
    {
        return !$this->isContaClasseA($conta);
    }
}
