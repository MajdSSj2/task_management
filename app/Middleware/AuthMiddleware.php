<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use App\ResponseFormatter;
use App\Exceptions\AuthException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
  use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFormatter $responseFormatter,
        private readonly ResponseFactoryInterface $response
    ) {}




public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{
    $authHeader = $request->getHeaderLine('Authorization');

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        return $this->errorResponse('Unauthorized: No token provided', 401);
    }

    $token = str_replace('Bearer ', '', $authHeader);

    try {
        $decoded = (array) JWT::decode($token, new Key($_ENV['SECRET_KEY'], 'HS256'));
        return $handler->handle($request->withAttribute('user', $decoded));

    } catch (ExpiredException $e) {
        return $this->errorResponse('Token has expired', 401);
    } catch (SignatureInvalidException $e) {
        return $this->errorResponse('Invalid token signature', 401);
    } catch (BeforeValidException $e) {
        return $this->errorResponse('Token not yet valid', 401);
    } catch (\UnexpectedValueException $e) {
        // This catches malformed tokens or structural issues
        return $this->errorResponse('Malformed or invalid token', 401);
    } 
}

private function errorResponse(string $message, int $code): ResponseInterface
{
    $response = $this->response->createResponse($code);
    $response->getBody()->write(json_encode(['message' => $message]));
    return $response->withHeader('Content-Type', 'application/json');
}
}
