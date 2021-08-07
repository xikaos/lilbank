<?php

namespace Tests\Feature;

use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;
use App\Repositories\Application\ContaDatabaseRepository;
use Database\Factories\ContaDatabaseFactory;
use Database\Factories\ContaFactory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContaDatabaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $contaFactory;
    protected $contaDatabaseRepository;
    protected $contaDatabaseFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->contaFactory = app()->make(ContaFactory::class);
        $this->contaDatabaseFactory = app()->make(ContaDatabaseFactory::class);
        $this->contaDatabaseRepository = app()->make(ContaDatabaseRepository::class);
    }

    public function testLocalizaContaPeloIdentificador()
    {
        $conta = $this->contaDatabaseFactory->make();

        $contaNoBancoDeDados = $this->contaDatabaseRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoBancoDeDados);
    }

    public function testLancaExcecaoAoBuscarContaNaoPersistida()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $conta = $this->contaFactory->make();

        $this->contaDatabaseRepository->getConta($conta->getIdentificador());
    }

    public function testLancaExcecaoAoBuscaContaNaoExistente()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $this->contaDatabaseRepository->getConta(Str::random(rand(12, 48)));
    }

    public function testSalvaContaNoBancoDeDados()
    {
        $conta = $this->contaFactory->make();

        $this->contaDatabaseRepository->salvarConta($conta);

        $contaNoBancoDeDados = $this->contaDatabaseRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoBancoDeDados);
    }

    public function testLancaExcecaoNaOcorrenciaDeFalhaAoSalvar()
    {
        $this->expectException(ContaNaoSalvaException::class);

        $conta = $this->contaFactory->make();

        DB::shouldReceive('table')
            ->with($this->contaDatabaseRepository->getNomeDaTabela())
            ->andThrows(new Exception());

        $this->contaDatabaseRepository->salvarConta($conta);
    }
}
