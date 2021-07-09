<?php

namespace App\Exceptions;

use Exception;

class ContaNaoEncontradaException extends Exception
{
    public function __construct(string $identificador)
    {
        $this->messsage = "Conta com o identificador $identificador não encontrada.";
    }
}
