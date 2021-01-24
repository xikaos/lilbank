<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Contracts\Domain\TransferenciaServiceContract;

use App\Models\Conta;
use App\Models\Valor;

use App\Exceptions\LimiteInsuficienteException;

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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_transferencia_entre_contas()
    {
        $saldoContaOrigem = $this->faker->numberBetween(0, 100);
        $saldoContaDestino = $this->faker->numberBetween(0, 100);

        $contaOrigem = new Conta($saldoContaOrigem);
        $contaDestino = new Conta($saldoContaDestino);

        $valorTransferencia = new Valor($this->faker->numberBetween(0, $contaOrigem->getSaldoTotalDisponivel()));

        $saldoFinalOrigem = $saldoContaOrigem - $valorTransferencia->getQuantia();
        $saldoFinalDestino = $saldoContaDestino + $valorTransferencia->getQuantia();

        $this->transferenciaService->transferir(
            $contaOrigem,
            $contaDestino,
            $valorTransferencia
        );

        $this->assertEquals($saldoFinalOrigem, $contaOrigem->getSaldo());
        $this->assertEquals($saldoFinalDestino, $contaDestino->getSaldo());
    }

    public function test_nao_permite_transferencia_com_valor_maior_que_saldo_total_disponivel()
    {
        // Duplicate, refactor as soon as possible.
        $saldoContaOrigem = $this->faker->numberBetween(0, 100);
        $saldoContaDestino = $this->faker->numberBetween(0, 100);

        $limiteContaOrigem = $this->faker->numberBetween(0, 100);

        $contaOrigem = new Conta($saldoContaOrigem, $limiteContaOrigem);
        $contaDestino = new Conta($saldoContaDestino);

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
