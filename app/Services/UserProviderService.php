<?php

namespace App\Services;

use App\Contracts\AuthServiceProviderInterface;
use App\Entity\User;
use App\Exceptions\RegistrationException;
use Doctrine\ORM\EntityManager;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;

class UserProviderService implements UserProviderServiceInterface
{

    public function __construct(private readonly EntityManager $em,
    private readonly AuthServiceProviderInterface $auth,
    )
    {
     
    }

	public function getUserById(int $userId) : ?UserInterface 
    {
        return $this->em->find(User::class, $userId);
    }

    public function createUser(array $data): array
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($user) {
            throw new RegistrationException([
                'message' => 'email already exists',
            ], "email already exists" ,409);
        }

        $user = new User($data);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);
        $this->em->flush();

        $token = $this->auth->generateToken($user);

        return ['user' => $user, 'token' => $token];

    }
}
