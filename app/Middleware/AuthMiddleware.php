<?php

namespace App\Middleware;

use App\ResponseFormatter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFormatter $responseFormatter,
    private readonly ResponseFactoryInterface $response)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        // 1. Get the Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        // 2. Check if it exists and starts with "Bearer "
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Unauthorized: No token provided']));
            return $this->responseFormatter->response($response, 401);
        }

        // 3. Extract the token
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // 4. Decode/Verify the token (using your JWT secret)
            $decoded = (array) JWT::decode($token, new Key($_ENV['SECRET_KEY'], 'HS256'));
            $request = $request->withAttribute('user', $decoded);
            // If successful, proceed to the controller
            return $handler->handle($request);
        } catch (\Exception $e) {
            // 5. If token is invalid or expired
           // $response = new Response();
            $response = $this->response->createResponse();
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $this->responseFormatter->response($response, 401);
        }
    }
}