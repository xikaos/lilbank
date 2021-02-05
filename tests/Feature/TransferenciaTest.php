<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Models\Conta;
use App\Models\Valor;

use App\Exceptions\LimiteInsuficienteException;
use App\Exceptions\QuantiaInvalidaException;

class TransferenciaTest extends TestCase
{
    use WithFaker;

    private $transferenciaService;

    private function createService(TransferenciaServiceContract $transferenciaService)
    {
        $this->transferenciaService = $transferenciaService;
    }

    public function setUp() : void
    {
        parent::setUp();

        $this->transferenciaService = $this->app->make(TransferenciaServiceContract::class);
    }

    private function inicializaSaldosEContas()
    {
        $saldoContaOrigem = $this->faker->numberBetween(0, 100);
        $saldoContaDestino = $this->faker->numberBetween(0, 100);

        return [
            'saldoContaOrigem'  => $saldoContaOrigem,
            'saldoContaDestino' => $saldoContaDestino,
            'contaOrigem'       => new Conta($saldoContaOrigem),
            'contaDestino'      => new Conta($saldoContaDestino)
        ];
    }

    public function test_nao_permite_transferencia_de_valor_zero()
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

    public function test_transferencia_entre_contas()
    {
        [
            'saldoContaOrigem'  => $saldoContaOrigem,
            'saldoContaDestino' => $saldoContaDestino,
            'contaOrigem'       => $contaOrigem,
            'contaDestino'      => $contaDestino
        ] = $this->inicializaSaldosEContas();

        $valorTransferencia = new Valor($this->faker->numberBetween(0, $contaOrigem->getSaldoTotalDisponivel()));

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

    public function test_nao_permite_transferencia_com_valor_maior_que_saldo_total_disponivel()
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
