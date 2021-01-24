<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Conta;
use App\Contracts\Domain\TransferenciaServiceContract;

class TransferenciaTest extends TestCase
{
    use WithFaker;

    private $transferenciaService;
    private $contaOrigem;
    private $contaDestino;

    private $saldoContaOrigem;
    private $saldoContaDestino;

    private $valorTransferencia;

    private $saldoFinalOrigem;
    private $saldoFinalDestino;

    private function createService(TransferenciaServiceContract $transferenciaService)
    {
        $this->transferenciaService = $transferenciaService;
    }

    public function setUp() : void
    {
        parent::setUp();
        
        $this->transferenciaService = $this->app->make(TransferenciaServiceContract::class);


        $this->saldoContaOrigem = $this->faker->numberBetween(0, 100);
        $this->saldoContaDestino = $this->faker->numberBetween(0, 100);

        $this->contaOrigem = new Conta($this->saldoContaOrigem);
        $this->contaDestino = new Conta($this->saldoContaDestino);

        $this->valorTransferencia = $this->faker->numberBetween(0, 100);

        $this->saldoFinalOrigem = $this->saldoContaOrigem - $this->valorTransferencia;
        $this->saldoFinalDestino = $this->saldoContaDestino + $this->valorTransferencia;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_transferencia_entre_contas()
    {
        $this->transferenciaService->transferir(
            $this->contaOrigem,
            $this->contaDestino,
            $this->valorTransferencia
        );

        $this->assertEquals($this->saldoFinalOrigem, $this->contaOrigem->getSaldo());
        $this->assertEquals($this->saldoFinalDestino, $this->contaDestino->getSaldo());
    }
}
