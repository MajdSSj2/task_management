<?php

namespace App\Services;

use App\Contracts\TaskServiceProviderInterface;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class TaskServiceProvider implements TaskServiceProviderInterface
{
    public function __construct(private readonly EntityManager $em)
    {
    }

	public function createTask(User $user, array $data): Task 
    {
        $task = new Task($user, $data);
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

	public function getAllTasks(): array 
    {
       return $this->em->getRepository(Task::class)->findAll();
    }
}
