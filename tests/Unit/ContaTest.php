<?php

namespace Tests\Unit;

use Ramsey\Uuid\Uuid;

use App\Exceptions\LimiteInvalidoException;
use PHPUnit\Framework\TestCase;

use App\Models\Conta;
use App\Models\Valor;

class ContaTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaldoZeradoNaCriacaoDeConta()
    {
        $conta = new Conta(
            identificador: Uuid::uuid4()
        );

        $this->assertEquals(0, $conta->getSaldo());
    }

    public function testNaoPermiteInicializacaoComLimiteNegativo()
    {
        $this->expectException(LimiteInvalidoException::class);

        $limite = rand(-100, -1);

        new Conta(
            identificador: Uuid::uuid4(),
            limite: $limite
        );
    }

    public function testSaldoDiferenteDeZeroNaCriacaoComValorInicial()
    {
        $valorInicial = 100;
        $conta = new Conta(
            identificador: Uuid::uuid4(),
            saldo: $valorInicial
        );

        $this->assertEquals($valorInicial, $conta->getSaldo());
    }

    public function testCreditarValorNaConta()
    {
        $conta = new Conta(Uuid::uuid4());
        $valorCredito = new Valor(rand(0, 100));

        $conta->creditar($valorCredito);

        $this->assertEquals($valorCredito->getQuantia(), $conta->getSaldo());
    }

    public function testDebitarValorNaConta()
    {
        $valorInicial = 100;
        $valorDebito = new Valor(rand(0, 50));
        
        $conta = new Conta(
            identificador: Uuid::uuid4(),
            saldo: $valorInicial
        );
        
        $conta->debitar($valorDebito);
        
        $saldoFinalEsperado = $valorInicial - $valorDebito->getQuantia();

        $this->assertEquals($saldoFinalEsperado, $conta->getSaldo());
    }

    public function testCalculaSaldoTotalDisponivel()
    {
        $saldoInicial = rand(0,100);
        $limiteInicial = rand(0,100);

        $conta = new Conta(
            identificador: Uuid::uuid4(),
            saldo: $saldoInicial,
            limite: $limiteInicial
        );

        $saldoTotalDisponivel = $saldoInicial + $limiteInicial;

        $this->assertEquals($saldoTotalDisponivel, $conta->getSaldoTotalDisponivel());
    }
}
