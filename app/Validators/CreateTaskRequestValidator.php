<?php

namespace App\Validators;

use Valitron\Validator;
use App\Exceptions\ValidationException;
use App\Contracts\TaskRequestValidatorInterface;

class CreateTaskRequestValidator implements TaskRequestValidatorInterface
{
        public function __construct()
        {
            
        }

        public function validate(array $data): array 
        {
            $v = new Validator($data);

            $v->rule('required', ['title', 'due', 'priority',"done" ,'user_id']);
            $v->rule('lengthMax', 'title', 50)->message('title is required');
            $v->rule('lengthMax', 'description', 255);
            $v->rule('dateFormat', 'due', 'd-m-Y g:i:s A');

             if (! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}