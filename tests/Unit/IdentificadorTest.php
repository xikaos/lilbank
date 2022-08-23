<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Entities\Identificador;

class IdentificadorTest extends TestCase
{
    public function testInicializaIdentificadorComValorNaoNulo()
    {
        $identificador = new Identificador();

        $this->assertNotNull($identificador->getIdentificador());
    }

    public function testInicializaIdentificadorComParametroPassado()
    {
        $valorDoIdentificador = 'identificador-da-conta';

        $identificador = new Identificador($valorDoIdentificador);

        $this->assertEquals($valorDoIdentificador, $identificador->getIdentificador());
    }
}
