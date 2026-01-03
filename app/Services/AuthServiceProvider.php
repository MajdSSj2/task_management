<?php

namespace App\Services;

use App\Contracts\AuthServiceProviderInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\Exceptions\AuthException;
use Firebase\JWT\JWT;

class AuthServiceProvider implements AuthServiceProviderInterface
{

    public function __construct(private readonly UserProviderServiceInterface $userService)
    {
        
    }
    public function generateToken(UserInterface $user): string
    {
        $payload = [
            'iss' => 'localhost.com',
            'sub' => $user->getId(),
            'iat' => time(),
            'exp' => time() + 3600
        ];
        return JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

    }

	public function attemptLogin(string $email, string $password): UserInterface 
    {
        $user = $this->userService->getUserByEmail($email);
        if (!password_verify($password, $user->getPassword())) {
            throw new AuthException([], "Email or password are incorrect",400 );
        }
        
        return $user;
    }
}