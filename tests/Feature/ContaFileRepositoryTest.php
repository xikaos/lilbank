<?php

namespace Tests\Feature;

use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;
use App\Repositories\Application\ContaDatabaseRepository;
use App\Repositories\Application\ContaFileRepository;
use Database\Factories\ContaFactory;
use Database\Factories\ContaFileFactory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContaFileRepositoryTest extends TestCase
{

    protected $contaFactory;
    protected $contaFileFactory;

    protected $contaFileRepository;

    protected $contaNoSistemaDeArquivos;

    public function setUp(): void
    {
        parent::setUp();

        $this->contaFactory = app()->make(ContaFactory::class);
        $this->contaFileFactory = app()->make(ContaFileFactory::class);
        $this->contaFileRepository = app()->make(ContaFileRepository::class);
    }

    public function testLocalizaContaPeloIdentificador()
    {
        $conta = $this->contaFileFactory->make();

        $contaNoSistemaDeArquivos = $this->contaFileRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoSistemaDeArquivos);
    }

    public function testLancaExcecaoAoBuscarContaNaoPersistida()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $conta = $this->contaFactory->make();

        $contaNoSistemaDeArquivos = $this->contaFileRepository->getConta($conta->getIdentificador());
    }

    public function testLancaExcecaoAoBuscarContaInexistente()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $this->contaFileRepository->getConta(Str::random(rand(12, 48)));
    }

    public function testSalvaContaNoSistemaDeArquivos()
    {
        $conta = $this->contaFactory->make();

        $discoFake = Storage::fake('contas');

        Storage::shouldReceive('disk')
            ->with('contas')
            ->andReturn($discoFake);

        $this->contaFileRepository->salvarConta($conta);

        $contaNoSistemaDeArquivos = $this->contaFileRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoSistemaDeArquivos);
    }

    public function testLancaExcecaoNaOcorrenciaDeFalhaAoSalvar()
    {
        $this->expectException(ContaNaoSalvaException::class);

        $conta = $this->contaFactory->make();

        Storage::shouldReceive('disk')
            ->with($this->contaFileRepository->getDisco())
            ->andThrows(new Exception());

        $this->contaFileRepository->salvarConta($conta);
    }
}