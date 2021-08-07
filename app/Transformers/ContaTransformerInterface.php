<?php

namespace App\Transformers;

use App\Entities\Conta;

interface ContaTransformerInterface
{
    public function formatar(Conta $conta);
    public function converter($entrada);
}