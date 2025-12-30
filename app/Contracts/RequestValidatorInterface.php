<?php

namespace App\Contracts;

interface TaskRequestValidatorInterface 
{
    public function validate(array $data) : array;
}
