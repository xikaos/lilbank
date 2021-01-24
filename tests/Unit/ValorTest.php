<?php

namespace Tests\Unit;

use App\Exceptions\QuantiaInvalidaException;
use PHPUnit\Framework\TestCase;

use App\Models\Valor;

class ValorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_falha_inicializacao_com_valor_zero()
    {
        $this->expectException(QuantiaInvalidaException::class);
        new Valor(0);
    }

    public function test_falha_inicializacao_com_valor_negativo()
    {
        $this->expectException(QuantiaInvalidaException::class);
        new Valor(rand(-100,-1));
    }

    public function test_inicializa_com_valores_positivos()
    {
        $quantia = rand(1,100);

        $valor = new Valor($quantia);

        $this->assertEquals($valor->getQuantia(), $quantia);
    }
}
