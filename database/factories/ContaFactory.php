<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;

use App\Models\Conta;

class ContaFactory
{
    const SALDO_NEGATIVO_MAXIMO = -1000;
    const SALDO_POSITIVO_MAXIMO = 1000;

    const LIMITE_POSITIVO_MAXIMO = 1000;

    public function make($conta = null) : Conta
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

    public function atributosAleatorios() : Array
    {
        return [
            'identificador' => Uuid::uuid4(),
            'saldo'         => rand(self::SALDO_NEGATIVO_MAXIMO, self::SALDO_POSITIVO_MAXIMO),
            'limite'        => rand(0, self::LIMITE_POSITIVO_MAXIMO)
        ];
    }
}
