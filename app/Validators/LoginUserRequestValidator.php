<?php

namespace App\Validators;

use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use Valitron\Validator;

class LoginUserRequestValidator implements RequestValidatorInterface
{

    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', [ 'email', 'password']);
        $v->rule('lengthMax', ['email'],255);
        $v->rule('lengthMin', ['password'],8);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }
        return $data;
    }
}