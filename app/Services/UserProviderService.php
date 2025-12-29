<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;

class UserProviderService implements UserProviderServiceInterface
{

    public function __construct(private readonly EntityManager $em)
    {
     
    }

	public function getUserById(int $userId) : ?UserInterface 
    {
        return $this->em->find(User::class, $userId);
    }
}
