<?php

namespace App\Exceptions;

use Throwable;

class RegistrationException extends \Exception
{

    public readonly array $errors;

    public function __construct(
        array|string $errors = [],
        string $message = "Registration failed",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }
}