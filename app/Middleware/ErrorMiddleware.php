<?php

namespace App\Middleware;

use App\Exceptions\NotFoundException;
use App\ResponseFormatter;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFormatter $responseFormatter,
    private readonly ResponseFactoryInterface $response)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        try {
           return $handler->handle($request);
        } catch (NotFoundException $e) {
                $response = $this->response->createResponse($e->getCode());
            $response->getBody()->write(
                json_encode([
                    'message' => $e->getMessage()
                ])
            );
            return $response->withHeader('Content-Type', 'application/json');
        }
         catch(\Exception $e){
                $response = $this->response->createResponse($e->getCode());
            $response->getBody()->write(
                json_encode([
                    'message' => $e->getMessage(),
                ])
            );
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}