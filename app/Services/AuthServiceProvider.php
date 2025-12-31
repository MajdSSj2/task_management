<?php

namespace App\Services;

use App\Contracts\AuthServiceProviderInterface;
use App\Contracts\UserInterface;
use Firebase\JWT\JWT;

class AuthServiceProvider implements AuthServiceProviderInterface
{

    public function generateToken(UserInterface $user): string
    {
        $payload = [
            'iss' => 'localhost.com',
            'sub' => $user->getId(),
            'iat' => time(),
            'exp' => time() + 36
        ];
        return JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

    }
}