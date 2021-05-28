<?php

namespace App\Repositories\Domain;

use App\Contracts\Application\ContaRepositoryContract;
use App\Contracts\Domain\ContaRepositoryContract as ContaDomainRepositoryContract;
use App\Exceptions\ContaNaoEncontradaException;
use App\Models\Conta;
use App\Repositories\Application\ContaDatabaseRepository;
use App\Repositories\Application\ContaFileRepository;
use Illuminate\Support\Collection;

class ContaRepository implements ContaDomainRepositoryContract
{
    private ContaRepositoryContract $contaRepository;
    private Collection $repositorios;

    public function __construct()
    {
        $this->repositorios = new Collection(
            [
                ContaDatabaseRepository::class,
                ContaFileRepository::class
            ]
        );

        $this->contaRepository = app($this->repositorios->first());
    }

    public function getConta(string $identificador): Conta
    {
        try {
            return $this->contaRepository->getConta($identificador);
        } catch (ContaNaoEncontradaException $exception) {
            if ($this->isUltimaImplementacaoDoRepositorio()) {
                throw $exception;
            }

            $this->mudaImplementacaoDoRepositorio();

            return $this->getConta($identificador);
        }
    }

    public function salvarConta(Conta $conta): void
    {
        
    }

    private function mudaImplementacaoDoRepositorio(): void
    {
        $proximasImplementacoes = $this->repositorios->skipUntil(
            function ($repositorio) {
                return get_class($this->contaRepository) != $repositorio;
            }
        );

        $this->contaRepository = app($proximasImplementacoes->first());
    }

    private function isUltimaImplementacaoDoRepositorio(): bool
    {
        return get_class($this->contaRepository) == $this->repositorios->last();
    }
}
