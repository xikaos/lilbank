<?php

namespace App\Repositories\Application;

use Exception;

use Illuminate\Support\Facades\DB;

use App\Contracts\Application\ContaRepositoryContract;

use App\Models\Conta;

use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;

// I don't like namespacing this factory into Database. Need to change later.
use Database\Factories\ContaFactory;


class ContaDatabaseRepository implements ContaRepositoryContract {
    protected $nomeDaTabela;
    protected $contaFactory;

    public function __construct(ContaFactory $contaFactory)
    {
        $this->nomeDaTabela = 'contas';
        $this->contaFactory = $contaFactory;
    }

    public function getConta(string $identificador) : ?Conta
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

    public function salvarConta(Conta $conta) : void
    {
        try {
            DB::table($this->nomeDaTabela)->insert(
                [
                    'identificador' => $conta->getIdentificador(),
                    'saldo'         => $conta->getSaldo(),
                    'limite'        => $conta->getLimite()
                ]
            );
        } 
        catch (Exception $exception) {
            throw new ContaNaoSalvaException($conta->getIdentificador());
        }
    }
}