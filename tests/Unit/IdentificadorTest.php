<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Entities\Identificador;
use App\Exceptions\IdentificadorInvalidoException;

class IdentificadorTest extends TestCase
{
    public function testNaoInicializaComStringNula()
    {
        $this->expectException(IdentificadorInvalidoException::class);

        new Identificador("");
    }

    public function testInicializaIdentificadorComParametroInvalido()
    {
        $this->expectException(IdentificadorInvalidoException::class);

        new Identificador('identificador-invalido');
    }

    public function testInicializaIdentificadorComParametroValido()
    {
        $identificadorValido = Identificador::gerar();
        $identificador = new Identificador($identificadorValido);

        $this->assertNotNull($identificador);
        $this->assertEquals($identificadorValido, $identificador->getValor());
    }
}
