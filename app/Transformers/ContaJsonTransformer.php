<?php

namespace App\Transformers;

use App\Models\Conta;

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

        return new Conta(
            identificador: $contaObjeto->identificador,
            saldo: $contaObjeto->saldo,
            limite: $contaObjeto->limite
        );
    }
}