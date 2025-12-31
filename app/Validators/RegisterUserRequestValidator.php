<?php

namespace App\Validators;

use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use Valitron\Validator;

class RegisterUserRequestValidator implements RequestValidatorInterface
{

    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['name', 'email', 'password']);
        $v->rule('lengthMax', ['name','email'],255);
        $v->rule('lengthMin', ['password'],8);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }
        return $data;
    }
}