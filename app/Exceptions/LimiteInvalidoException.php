<?php

namespace App\Exceptions;

use Exception;

class LimiteInvalidoException extends Exception
{
    protected $message = 'Limite deve ser maior ou igual a zero.';
}
