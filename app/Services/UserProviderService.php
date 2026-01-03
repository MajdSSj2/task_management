<?php

namespace App\Services;

use App\Contracts\AuthServiceProviderInterface;
use App\Entity\User;
use App\Exceptions\AuthException;
use Doctrine\ORM\EntityManager;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;

class UserProviderService implements UserProviderServiceInterface
{

    public function __construct(private readonly EntityManager $em) {}

    public function getUserById(int $userId): ?UserInterface
    {
        return $this->em->find(User::class, $userId);
    }

    public function createUser(array $data): UserInterface
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($user) {
            throw new AuthException([
                'message' => 'email already exists',
            ], "email already exists", 409);
        }

        $user = new User($data);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);
        $this->em->flush();


        return  $user;
    }

    public function getUserByEmail(string $email): ?UserInterface
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw new AuthException([
                'credentials' => ['Invalid email or password']
            ], "Email or password incorrect", 400);
        }
        return $user;
    }
}
