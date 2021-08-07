<?php

namespace Database\Factories;

use App\Entities\Conta;
use App\Models\Conta as ContaModel;
use Carbon\Carbon;

class ContaDatabaseFactory
{
    protected $contaFactory;

    public function __construct()
    {
        $this->contaFactory = app()->make(ContaFactory::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function make(): Conta
    {
        $conta = $this->contaFactory->make();

        ContaModel::create(
            [
            'identificador' => $conta->getIdentificador(),
            'saldo'         => $conta->getSaldo(),
            'limite'        => $conta->getLimite(),
            'created_at'    => Carbon::now()
            ]
        );

        return $conta;
    }
}
