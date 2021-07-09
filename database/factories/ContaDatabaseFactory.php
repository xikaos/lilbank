<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Conta;

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
    public function make() : Conta
    {
        $conta = $this->contaFactory->make();

        DB::table('contas')->insert([
            'identificador' => $conta->getIdentificador(),
            'saldo'         => $conta->getSaldo(),
            'limite'        => $conta->getLimite(),
            'created_at'    => Carbon::now()
        ]);

        return $conta;
    }
}
