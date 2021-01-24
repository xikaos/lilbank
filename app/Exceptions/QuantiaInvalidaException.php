<?php

namespace App\Exceptions;

use Exception;

class QuantiaInvalidaException extends Exception
{
    protected $message = 'Quantia informada deve ser maior que zero.';
}
