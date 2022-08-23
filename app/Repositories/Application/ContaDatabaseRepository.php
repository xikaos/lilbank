<?php

namespace App\Repositories\Application;

use App\Contracts\Application\ContaRepositoryContract;
use App\Entities\Conta;
use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;
use App\Models\Conta as ContaModel;
use Database\Factories\ContaFactory;
use Exception;
use PhpParser\Node\Expr\Throw_;
use Throwable;

class ContaDatabaseRepository implements ContaRepositoryContract
{
    protected $nomeDaTabela;
    protected $contaFactory;

    public function __construct(
        ContaModel $contaModel,
        ContaFactory $contaFactory,
        string $nomeDaTabela = 'contas'
    ) {
        $this->contaModel = $contaModel;
        $this->nomeDaTabela = $nomeDaTabela;
        $this->contaFactory = $contaFactory;
    }

    public function getConta(string $identificador): ?Conta
    {
        $conta = $this->contaModel->where('identificador', $identificador)->first();

        if (! isset($conta)) {
            throw new ContaNaoEncontradaException($identificador);
        }

        // TODO: Mapper?
        return $this->contaFactory->make($conta);
    }

    public function salvarConta(Conta $conta): void
    {
        try {
            $this->contaModel->updateOrCreate(
                [
                    'identificador' => $conta->getIdentificador(),
                    'saldo'         => $conta->getSaldo(),
                    'limite'        => $conta->getLimite()
                ]
            );
        } catch (Throwable $exception) {
            throw new ContaNaoSalvaException($conta->getIdentificador());
        }
    }
}
