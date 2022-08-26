<?php

namespace App\Entities;

use App\Exceptions\IdentificadorInvalidoException;

// Maybe this could be abstracted in an interface?
// Switching UUID version based on saldo seems evil T_T
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Rfc4122\Validator as Rfc4122Validator;

class Identificador
{
    private string $identificador;

    public static function gerar(): string
    {
        return Uuid::uuid4();
    }

    public function __construct(string $identificador)
    {
        if (empty($identificador)) {
            throw IdentificadorInvalidoException::identificadorNulo();
        }

        $factory = new UuidFactory();
        $factory->setValidator(new Rfc4122Validator());

        Uuid::setFactory($factory);

        if (!Uuid::isValid($identificador)) {
            throw new IdentificadorInvalidoException($identificador);
        }

        $this->identificador = $identificador;
    }

    public function __toString(): string
    {
        return $this->identificador;
    }
}
