<?php

namespace App\Transformers;

use App\Entities\Conta;
use App\Entities\Identificador;

use App\Transformers\ContaTransformerInterface;

class ContaJsonTransformer implements ContaTransformerInterface
{
    public function formatar(Conta $conta): string
    {
        return json_encode(
            [
                'identificador' => $conta->getIdentificador(),
                'saldo'         => $conta->getSaldo(),
                'limite'        => $conta->getLimite(),
            ]
        );
    }

    public function converter($jsonConta): ?Conta
    {
        $contaObjeto = json_decode($jsonConta);

        $identificador = new Identificador($contaObjeto->identificador);
        return new Conta($identificador, $contaObjeto->saldo, $contaObjeto->limite);
    }
}