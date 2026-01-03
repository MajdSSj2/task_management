<?php

namespace App\Contracts;

interface AuthServiceProviderInterface
{
    public function generateToken(UserInterface $user): string;
    public function attemptLogin(string $email, string $password): ?UserInterface;
}
