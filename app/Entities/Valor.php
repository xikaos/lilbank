<?php

namespace App\Entities;

use App\Exceptions\QuantiaInvalidaException;

class Valor
{
    protected $quantia;

    public function __construct(int $quantia)
    {
        $this->validarQuantia($quantia);

        $this->quantia = $quantia;
    }

    public function setQuantia(int $quantia) : void
    {
        $this->validarQuantia($quantia);

        $this->quantia = $quantia;
    }

    public function getQuantia() : int 
    {
        return $this->quantia;
    }

    private function validarQuantia(int $quantia)
    {
        if ($quantia <= 0 ) {
            throw new QuantiaInvalidaException();
        }
        return;
    }
}