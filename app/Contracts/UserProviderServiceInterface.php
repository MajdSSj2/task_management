<?php

namespace App\Contracts;

interface UserProviderServiceInterface
{
    public function getUserById(int $userId): ?UserInterface;
}
