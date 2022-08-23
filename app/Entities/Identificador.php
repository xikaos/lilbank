<?php

namespace App\Entities;

use Ramsey\Uuid\Uuid;

class Identificador
{
    private string $identificador;

    public function __construct(string $identificador = null)
    {
        $this->identificador = $identificador ?? self::gerarIdentificador();
    }

    private static function gerarIdentificador(): string
    {
        return Uuid::uuid4();
    }

    public function getIdentificador(): string
    {
        return $this->identificador;
    }
}
