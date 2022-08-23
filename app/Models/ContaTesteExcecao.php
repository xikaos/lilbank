<?php

namespace App\Models;

use Exception;

class ContaTesteExcecao extends Conta
{
    public function updateOrCreate($attributes)
    {
        throw new Exception('Problema ao executar updateOrCreate');
    }
}
