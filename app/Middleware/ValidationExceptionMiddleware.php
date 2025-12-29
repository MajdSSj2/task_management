<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory)
    {
       
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = $this->responseFactory->createResponse(422);
            $response->getBody()->write(
                json_encode([
                    'errors' => $e->errors
                ])
            );
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
