<?php

namespace App\Exceptions;

use Exception;

class ContaNaoSalvaException extends Exception
{
    protected $message;

    public function __construct(string $identificador)
    {
        $this->message = "Não foi possível salvar a conta com o identificador $identificador.";
    }
}
