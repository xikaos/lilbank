<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Models\Conta;

class ContaTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaldoZeradoNaCriacaoDeConta()
    {
        $conta = new Conta();

        $this->assertEquals(0, $conta->getSaldo());
    }

    public function testSaldoDiferenteDeZeroNaCriacaoComValorInicial()
    {
        $valorInicial = 100;
        $conta = new Conta($valorInicial);

        $this->assertEquals($valorInicial, $conta->getSaldo());
    }

    public function testCreditarValorNaConta()
    {
        $conta = new Conta();
        $valorCredito = 100;

        $conta->creditar($valorCredito);

        $this->assertEquals($valorCredito, $conta->getSaldo());
    }

    public function testDebitarValorNaConta()
    {
        $valorInicial = 100;
        $valorDebito = 50;
        
        $conta = new Conta($valorInicial);
        
        $conta->debitar($valorDebito);
        
        $saldoFinalEsperado = $valorInicial - $valorDebito;

        $this->assertEquals($saldoFinalEsperado, $conta->getSaldo());
    }
}
