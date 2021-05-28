<?php

namespace App\Transformers;

use App\Models\Conta;

interface ContaTransformerInterface
{
    public function formatar(Conta $conta);
    public function converter($entrada);
}