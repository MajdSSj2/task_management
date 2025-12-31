<?php

namespace App\Validators;

use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Container\ContainerInterface;

use App\Contracts\RequestValidatorInterface;

class RequestValidatorFactory  implements RequestValidatorFactoryInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
        
    }

	public function make(string $class): RequestValidatorInterface
    {
        $validator = $this->container->get($class);
        
        if ($validator  instanceof RequestValidatorInterface ) {
            return $validator;
        }

        throw new \RuntimeException('Failed to instantiate the request validator class "' . $class . '"');
    }
}
