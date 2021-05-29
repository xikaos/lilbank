<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Storage;
use Database\Factories\ContaFactory;
use App\Transformers\ContaTransformerInterface;


class ContaFileFactory
{
    public function __construct(
        ContaFactory $contaFactory,
        ContaTransformerInterface $contaTransformer
    ) {
        $this->contaFactory = $contaFactory;
        $this->contaTransformer = $contaTransformer;
    }

    public function make()
    {
        $conta = $this->contaFactory->make();
        $contaTransformada = $this->contaTransformer
            ->formatar($conta);

        Storage::fake('contas')->put(
            $conta->getIdentificador(),
            $contaTransformada
        );

        return $conta;
    }
}
