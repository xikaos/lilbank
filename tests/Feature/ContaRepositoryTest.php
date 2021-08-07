<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Domain\ContaRepository;
use App\Repositories\Application\ContaFileRepository;
use App\Repositories\Application\ContaDatabaseRepository;
use Tests\TestCase;
use Database\Factories\ContaDatabaseFactory;
use Database\Factories\ContaFileFactory;
use Database\Factories\ContaFactory;
use App\Exceptions\ContaNaoEncontradaException;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ContaRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->contaRepository = app(ContaRepository::class);
        $this->contaFileRepository = app(ContaFileRepository::class);
        $this->contaDatabaseRepository = app(ContaDatabaseRepository::class);

        $this->contaFactory = app()->make(ContaFactory::class);
        $this->contaFileFactory = app()->make(ContaFileFactory::class);
        $this->contaDatabaseFactory = app()->make(ContaDatabaseFactory::class);
    }

    public function testBuscaContaNoBancoDeDados()
    {
        $conta = $this->contaDatabaseFactory->make();

        $this->assertEquals($conta, $this->contaRepository->getConta($conta->getIdentificador()));
    }

    public function testBuscaContaNoSistemaDeArquivos()
    {
        $conta = $this->contaFileFactory->make();

        $this->assertEquals(
            $conta,
            $this->contaFileRepository->getConta($conta->getIdentificador())
        );
    }

    public function testLancaExcecaoAoBuscarContaInexistente()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $this->contaRepository->getConta(Str::random(rand(12, 48)));
    }

    public function testSalvaContaCategoriaANoBancoDeDados()
    {
        $contaCategoriaA = $this->contaFactory->makeContaCategoriaA();

        $this->contaRepository->salvarConta($contaCategoriaA);

        $this->assertEquals(
            $this->contaDatabaseRepository->getConta($contaCategoriaA->getIdentificador()),
            $contaCategoriaA
        );
    }

    public function testSalvaContaCategoriaBNoSistemaDeArquivos()
    {
        $contaCategoriaB = $this->contaFactory->makeContaCategoriaB();

        $discoFake = Storage::fake('contas');

        Storage::shouldReceive('disk')
            ->with('contas')
            ->andReturn($discoFake);

        $this->contaRepository->salvarConta($contaCategoriaB);

        $this->assertEquals(
            $this->contaFileRepository->getConta($contaCategoriaB->getIdentificador()),
            $contaCategoriaB
        );
    }
}
