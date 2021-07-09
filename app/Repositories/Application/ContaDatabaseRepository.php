<?php

namespace App\Repositories\Application;

use App\Contracts\Application\ContaRepositoryContract;
use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;
use App\Models\Conta;
use Database\Factories\ContaFactory;
use Exception;
use Illuminate\Support\Facades\DB;

class ContaDatabaseRepository implements ContaRepositoryContract
{
    protected $nomeDaTabela;
    protected $contaFactory;

    public function __construct(ContaFactory $contaFactory)
    {
        $this->nomeDaTabela = 'contas';
        $this->contaFactory = $contaFactory;
    }

    public function getConta(string $identificador): ?Conta
    {
        $conta = DB::table($this->nomeDaTabela)
            ->where('identificador', $identificador)
            ->get()
            ->first();

        if (! isset($conta)) {
            throw new ContaNaoEncontradaException($identificador);
        }

        return $this->contaFactory->make($conta);
    }

    public function salvarConta(Conta $conta): void
    {
        try {
            DB::table($this->nomeDaTabela)->insert(
                [
                    'identificador' => $conta->getIdentificador(),
                    'saldo'         => $conta->getSaldo(),
                    'limite'        => $conta->getLimite()
                ]
            );
        } catch (Exception $exception) {
            throw new ContaNaoSalvaException($conta->getIdentificador());
        }
    }

    public function getNomeDaTabela()
    {
        return $this->nomeDaTabela;
    }
}
