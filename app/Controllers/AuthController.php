<?php

declare(strict_types=1);
namespace App\Controllers;


use App\Contracts\RequestValidatorFactoryInterface;
use App\ResponseFormatter;
use App\Services\UserProviderService;
use App\Validators\RegisterUserRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly UserProviderService $userService,
        private readonly  ResponseFormatter $responseFormatter,
    )
    {

    }

    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)->validate(
            $request->getParsedBody());

        $newUser = $this->userService->createUser($data);
        $response->getBody()->write(json_encode([
            'message' => 'User created successfully',
            'token' => $newUser['token'],
            'user' => [
                'id' => $newUser['user']->getId(),
                'email' =>$newUser['user']->getEmail(),
            ]
        ]));

        return $this->responseFormatter->response($response, 201);
    }

}