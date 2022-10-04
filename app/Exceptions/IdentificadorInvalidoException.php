<?php

namespace App\Exceptions;

use Exception;

class IdentificadorInvalidoException extends Exception
{
    protected $message;

    public static function identificadorNulo()
    {
        return new IdentificadorInvalidoException("Identificador inicializado com valor nulo.");
    }

    public function __construct(string $identificador)
    {
        $this->message = "Identificador inválido: $identificador.";
    }
}
