<?php

namespace App\Repositories\Domain;

use App\Contracts\Application\ContaRepositoryContract;
use App\Contracts\Domain\ContaRepositoryContract as ContaDomainRepositoryContract;
use App\Exceptions\ContaNaoEncontradaException;
use App\Entities\Conta;
use App\Policies\Domain\CategoriaContaPolicy;
use App\Repositories\Application\ContaDatabaseRepository;
use App\Repositories\Application\ContaFileRepository;
use Illuminate\Support\Collection;
use App\Enums\Domain\CategoriaContaEnum;

class ContaRepository implements ContaDomainRepositoryContract
{
    private ContaRepositoryContract $contaRepository;
    private Collection $repositorios;
    private CategoriaContaPolicy $categoriaContaPolicy;

    public function __construct(CategoriaContaPolicy $categoriaContaPolicy)
    {
        $this->repositorios = new Collection(
            [
                ContaDatabaseRepository::class,
                ContaFileRepository::class
            ]
        );

        $this->contaRepository = app($this->repositorios->first());

        $this->categoriaContaPolicy = $categoriaContaPolicy;
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
        $categoria = $this->categoriaContaPolicy->categoriza($conta);

        if ($categoria == CategoriaContaEnum::CATEGORIA_A) {
            $this->contaRepository = app(ContaDatabaseRepository::class);
        }

        if ($categoria == CategoriaContaEnum::CATEGORIA_B) {
            $this->contaRepository = app(ContaFileRepository::class);
        }

        $this->contaRepository->salvarConta($conta);
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
