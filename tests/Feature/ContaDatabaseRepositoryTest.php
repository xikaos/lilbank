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

    public function setUp() : void
    {
        parent::setUp();

        $this->contaFactory = app()->make(ContaFactory::class);
        $this->contaDatabaseFactory = app()->make(ContaDatabaseFactory::class);
        $this->contaDatabaseRepository = app()->make(ContaDatabaseRepository::class);
    }

    public function test_localiza_conta_pelo_identificador()
    {
        $conta = $this->contaDatabaseFactory->make();

        $contaNoBancoDeDados = $this->contaDatabaseRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoBancoDeDados);
    }

    public function test_lanca_excecao_ao_buscar_conta_nao_persistida()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $conta = $this->contaFactory->make();

        $this->contaDatabaseRepository->getConta($conta->getIdentificador());
    }

    public function test_lanca_excecao_ao_buscar_conta_inexistente()
    {
        $this->expectException(ContaNaoEncontradaException::class);

        $this->contaDatabaseRepository->getConta(Str::random(rand(12, 48)));
    }

    public function test_salva_conta_no_banco_de_dados()
    {
        $conta = $this->contaFactory->make();

        $this->contaDatabaseRepository->salvarConta($conta);

        $contaNoBancoDeDados = $this->contaDatabaseRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoBancoDeDados);
    }

    public function test_lanca_excecao_na_ocorrencia_de_falha_ao_salvar()
    {
        $this->expectException(ContaNaoSalvaException::class);

        $conta = $this->contaFactory->make();

        DB::shouldReceive('table')
            ->with($this->contaDatabaseRepository->getNomeDaTabela())
            ->andThrows(new Exception());

        $this->contaDatabaseRepository->salvarConta($conta);
    }
}
