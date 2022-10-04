<?php

namespace App\Entities;

use App\Contracts\Domain\ValorObjeto;

use App\Exceptions\IdentificadorInvalidoException;

// @TODO - Abstract this dependency
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Rfc4122\Validator as Rfc4122Validator;

class Identificador implements ValorObjeto
{
    private string $valor;

    public function getValor()
    {
        return $this->valor;
    }

    public static function gerar(): string
    {
        return Uuid::uuid4();
    }

    public function __construct(string $valor)
    {
        if (empty($valor)) {
            throw IdentificadorInvalidoException::identificadorNulo();
        }

        $factory = new UuidFactory();
        $factory->setValidator(new Rfc4122Validator());

        Uuid::setFactory($factory);

        if (!Uuid::isValid($valor)) {
            throw new IdentificadorInvalidoException($valor);
        }

        $this->valor = $valor;
    }
}
