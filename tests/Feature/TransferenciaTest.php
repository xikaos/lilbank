<?php

namespace Tests\Feature;

use Ramsey\Uuid\Uuid;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Entities\Conta;
use App\Entities\Valor;

use App\Exceptions\LimiteInsuficienteException;
use App\Exceptions\QuantiaInvalidaException;

class TransferenciaTest extends TestCase
{
    use WithFaker;

    private $transferenciaService;

    public function setUp(): void
    {
        parent::setUp();

        $this->transferenciaService = $this->app->make(TransferenciaServiceContract::class);
    }

    private function inicializaSaldosEContas()
    {
        $saldoContaOrigem = $this->faker->numberBetween(0, 100);
        $saldoContaDestino = $this->faker->numberBetween(0, 100);

        $contaOrigem = new Conta(
            identificador: Uuid::uuid4(),
            saldo: $saldoContaOrigem
        );

        $contaDestino = new Conta(
            identificador: Uuid::uuid4(),
            saldo: $saldoContaDestino
        );

        return [
            'saldoContaOrigem'  => $saldoContaOrigem,
            'saldoContaDestino' => $saldoContaDestino,
            'contaOrigem'       => $contaOrigem,
            'contaDestino'      => $contaDestino
        ];
    }

    public function testNaoPermiteTransferenciaDeValorZero()
    {
        [
            'saldoContaOrigem'  => $saldoContaOrigem,
            'saldoContaDestino' => $saldoContaDestino,
            'contaOrigem'       => $contaOrigem,
            'contaDestino'      => $contaDestino
        ] = $this->inicializaSaldosEContas();

        $this->expectException(QuantiaInvalidaException::class);

        $this->transferenciaService->transferir(
            $contaOrigem,
            $contaDestino,
            new Valor(0)
        );
    }

    public function testTransferenciaEntreContas()
    {
        [
            'saldoContaOrigem'  => $saldoContaOrigem,
            'saldoContaDestino' => $saldoContaDestino,
            'contaOrigem'       => $contaOrigem,
            'contaDestino'      => $contaDestino
        ] = $this->inicializaSaldosEContas();

        $valorTransferencia = new Valor($this->faker->numberBetween(1, $contaOrigem->getSaldoTotalDisponivel()));

        $this->transferenciaService->transferir(
            $contaOrigem,
            $contaDestino,
            $valorTransferencia
        );

        $saldoFinalOrigem = $saldoContaOrigem - $valorTransferencia->getQuantia();
        $saldoFinalDestino = $saldoContaDestino + $valorTransferencia->getQuantia();

        $this->assertEquals($saldoFinalOrigem, $contaOrigem->getSaldo());
        $this->assertEquals($saldoFinalDestino, $contaDestino->getSaldo());
    }

    public function testNaoPermiteTransferenciaComValorMaiorQueOSaldoTotalDisponivel()
    {
        [
            'contaOrigem'   => $contaOrigem,
            'contaDestino'  => $contaDestino
        ] = $this->inicializaSaldosEContas();

        $limiteContaOrigem = $this->faker->numberBetween(0, 100);
        $contaOrigem->setLimite($limiteContaOrigem);

        $valorExcedente = $this->faker->numberBetween(1, 100);
        $valorTransferencia = new Valor($contaOrigem->getSaldoTotalDisponivel() + $valorExcedente);

        $this->expectException(LimiteInsuficienteException::class);

        $this->transferenciaService->transferir(
            $contaOrigem,
            $contaDestino,
            $valorTransferencia
        );
    }
}
