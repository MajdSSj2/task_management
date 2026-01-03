<?php

declare(strict_types=1);

namespace App\Controllers;


use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\UserProviderServiceInterface;
use App\ResponseFormatter;
use App\Services\AuthServiceProvider;
use App\Validators\LoginUserRequestValidator;
use App\Validators\RegisterUserRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly UserProviderServiceInterface $userService,
        private readonly  ResponseFormatter $responseFormatter,
        private readonly AuthServiceProvider $auth
    ) {}

    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $newUser = $this->userService->createUser($data);
        $token = $this->auth->generateToken($newUser);
        $response->getBody()->write(json_encode([
            'message' => 'User created successfully',
            'token' => $token,
            'user' => [
                'id' => $newUser->getId()
            ]
        ]));

        return $this->responseFormatter->response($response, 201);
    }

    public function login(Request $request, Response $response)
    {
        $data = $this->requestValidatorFactory->make(LoginUserRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $user = $this->auth->attemptLogin($data['email'], $data['password']);
        $token = $this->auth->generateToken($user);

        $response->getBody()->write(json_encode([
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
            ]
        ]));
                return $this->responseFormatter->response($response, 200);

    }
}
