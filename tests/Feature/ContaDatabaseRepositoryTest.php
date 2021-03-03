<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Factories\ContaFactory;
use Database\Factories\ContaDatabaseFactory;
use App\Repositories\Application\ContaDatabaseRepository;

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

    public function test_salva_conta_no_banco_de_dados()
    {
        $conta = $this->contaFactory->make();

        $this->contaDatabaseRepository->salvarConta($conta);

        $contaNoBancoDeDados = $this->contaDatabaseRepository->getConta($conta->getIdentificador());

        $this->assertEquals($conta, $contaNoBancoDeDados);
    }
}
