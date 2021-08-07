<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;
use App\Policies\Domain\CategoriaContaPolicy;
use App\Models\Conta;

class ContaFactory
{
    public const SALDO_NEGATIVO_MAXIMO = -1000;
    public const SALDO_POSITIVO_MAXIMO = 1000;
    public const LIMITE_POSITIVO_MAXIMO = 1000;

    public function make($conta = null): Conta
    {
        if (is_object($conta)) {
            return new Conta(
                $conta->identificador,
                $conta->saldo,
                $conta->limite
            );
        }

        [
            'identificador' => $identificador,
            'saldo'         => $saldo,
            'limite'        => $limite
        ] = $this->atributosAleatorios();

        return new Conta($identificador, $saldo, $limite);
    }

    public function atributosAleatorios(): array
    {
        return [
            'identificador' => Uuid::uuid4(),
            'saldo'         => rand(self::SALDO_NEGATIVO_MAXIMO, self::SALDO_POSITIVO_MAXIMO),
            'limite'        => rand(0, self::LIMITE_POSITIVO_MAXIMO)
        ];
    }

    public function makeContaCategoriaA(): Conta
    {
        [
            'identificador' => $identificador,
            'saldo'         => $saldo,
            'limite'        => $limite
        ] = $this->atributosContaCategoriaA();

        return new Conta($identificador, $saldo, $limite);
    }

    public function makeContaCategoriaB(): Conta
    {
        [
            'identificador' => $identificador,
            'saldo'         => $saldo,
            'limite'        => $limite
        ] = $this->atributosContaCategoriaB();

        return new Conta($identificador, $saldo, $limite);
    }

    public function atributosContaCategoriaA(): array
    {
        $saldo = CategoriaContaPolicy::SALDO_CONTA_CATEGORIA_A + rand(0, self::SALDO_POSITIVO_MAXIMO);

        return [
            'identificador' => Uuid::uuid4(),
            'saldo'         => $saldo,
            'limite'        => rand(0, self::LIMITE_POSITIVO_MAXIMO)
        ];
    }

    public function atributosContaCategoriaB(): array
    {
        $limiteSuperiorSaldo = rand(1, (CategoriaContaPolicy::SALDO_CONTA_CATEGORIA_A - 1));
        $saldo = rand(0, $limiteSuperiorSaldo);
        $limite = $limiteSuperiorSaldo - $saldo;

        return [
            'identificador' => Uuid::uuid4(),
            'saldo'         => $saldo,
            'limite'        => $limite
        ];
    }
}
