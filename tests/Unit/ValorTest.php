<?php

namespace Tests\Unit;

use App\Exceptions\QuantiaInvalidaException;
use PHPUnit\Framework\TestCase;

use App\Entities\Valor;

class ValorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFalhaInicializacaoComValorZero()
    {
        $this->expectException(QuantiaInvalidaException::class);
        new Valor(0);
    }

    public function testFalhaInicializacaoComValorNegativo()
    {
        $this->expectException(QuantiaInvalidaException::class);
        new Valor(rand(-100, -1));
    }

    public function testInicializaComValoresPositivos()
    {
        $quantia = rand(1, 100);

        $valor = new Valor($quantia);

        $this->assertEquals($valor->getQuantia(), $quantia);
    }
}
