<?php

namespace App\Repositories\Application;

use App\Contracts\Application\ContaRepositoryContract;
use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\ContaNaoSalvaException;
use App\Models\Conta;
use App\Transformers\ContaTransformerInterface;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class ContaFileRepository implements ContaRepositoryContract {
    private string $disco;
    private ContaTransformerInterface $contaTransformer;

    public function __construct(ContaTransformerInterface $contaTransformer, string $disco = 'contas')
    {
        $this->disco = $disco;
        $this->contaTransformer = $contaTransformer;
    }

    public function getConta(string $identificador) : ?Conta
    {
        try {
            $contaNoDisco = Storage::disk($this->disco)->get($identificador);
        } catch (FileNotFoundException $exception) {
            throw new ContaNaoEncontradaException($identificador);
        }
        
        return $this->contaTransformer->converter($contaNoDisco);
    }

    public function salvarConta(Conta $conta) : void
    {
        try {
            $contaTransformada = $this->contaTransformer->formatar($conta);

            Storage::disk($this->disco)->put(
                $conta->getIdentificador(),
                $contaTransformada
            );
        } catch (Exception $exception) {
            throw new ContaNaoSalvaException($conta->getIdentificador());
        }
    }

    public function getDisco()
    {
        return $this->disco;
    }
}