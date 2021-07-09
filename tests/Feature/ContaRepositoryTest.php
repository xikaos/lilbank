<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Domain\ContaRepository;
use Tests\TestCase;
use Database\Factories\ContaDatabaseFactory;
use Database\Factories\ContaFileFactory;
use Database\Factories\ContaFactory;
use App\Exceptions\ContaNaoEncontradaException;
use Illuminate\Support\Str;

class ContaRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->contaRepository = app(ContaRepository::class);
        $this->contaFactory = app()->make(ContaFactory::class);
        $this->contaDatabaseFactory = app()->make(ContaDatabaseFactory::class);
        $this->contaFileFactory = app()->make(ContaFileFactory::class);
    }

    public function test_busca_conta_no_banco_de_dados()
    {
        $conta = $this->contaDatabaseFactory->make();

        $this->assertEquals($conta, $this->contaRepository->getConta($conta->getIdentificador()));
    }

    public function test_busca_conta_no_sistema_de_arquivos()
    {
        $conta = $this->contaFileFactory->make();

        $this->assertEquals($conta, $this->contaRepository->getConta($conta->getIdentificador()));
    }

    public function test_lancao_excecao_ao_busca_conta_inexistente()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $this->contaRepository->getConta(Str::random(rand(12, 48)));
    }

    public function test_salva_conta_categoria_a_no_banco_de_dados()
    {
        $contaCategoriaA = $this->contaFactory->makeContaCategoriaA();

        $this->contaRepository->salvarConta($contaCategoriaA);

        $this->assertEquals(
            $this->contaRepository->getConta($contaCategoriaA->getIdentificador()),
            $contaCategoriaA
        );
    }

    public function test_salva_conta_categoria_b_no_sistema_de_arquivos()
    {
        $contaCategoriaB = $this->contaFactory->makeContaCategoriaB();

        $this->contaRepository->salvarConta($contaCategoriaB);

        $this->assertEquals(
            $this->contaRepository->getConta($contaCategoriaB->getIdentificador()),
            $contaCategoriaB
        );
    }
}
