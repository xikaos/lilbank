<?php

namespace Tests\Unit;

use Ramsey\Uuid\Uuid;

use App\Exceptions\LimiteInvalidoException;
use PHPUnit\Framework\TestCase;

use App\Entities\Conta;
use App\Entities\Valor;
use App\Entities\Identificador;

class ContaTest extends TestCase
{
    private $identificador;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->identificador = new Identificador(Identificador::gerar());
    }

    public function testSaldoZeradoNaCriacaoDeConta()
    {
        $conta = new Conta($this->identificador);

        $this->assertEquals(0, $conta->getSaldo());
    }

    public function testNaoPermiteInicializacaoComLimiteNegativo()
    {
        $this->expectException(LimiteInvalidoException::class);

        $limite = rand(-100, -1);

        new Conta($this->identificador, 0, $limite);
    }

    public function testSaldoDiferenteDeZeroNaCriacaoComValorInicial()
    {
        $valorInicial = 100;
        $conta = new Conta($this->identificador, $valorInicial);

        $this->assertEquals($valorInicial, $conta->getSaldo());
    }

    public function testCreditarValorNaConta()
    {
        $conta = new Conta($this->identificador);
        $valorCredito = new Valor(rand(0, 100));

        $conta->creditar($valorCredito);

        $this->assertEquals($valorCredito->getQuantia(), $conta->getSaldo());
    }

    public function testDebitarValorNaConta()
    {
        $valorInicial = 100;
        $valorDebito = new Valor(rand(0, 50));
        
        $conta = new Conta($this->identificador, $valorInicial);
        
        $conta->debitar($valorDebito);
        
        $saldoFinalEsperado = $valorInicial - $valorDebito->getQuantia();

        $this->assertEquals($saldoFinalEsperado, $conta->getSaldo());
    }

    public function testCalculaSaldoTotalDisponivel()
    {
        $saldoInicial = rand(0, 100);
        $limiteInicial = rand(0, 100);

        $conta = new Conta($this->identificador, $saldoInicial, $limiteInicial);

        $saldoTotalDisponivel = $saldoInicial + $limiteInicial;

        $this->assertEquals($saldoTotalDisponivel, $conta->getSaldoTotalDisponivel());
    }
}
