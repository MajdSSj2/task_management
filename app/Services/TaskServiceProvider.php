<?php

namespace App\Services;

use App\Contracts\TaskServiceProviderInterface;
use App\Entity\Task;
use App\Entity\User;
use App\Exceptions\NotFoundException;
use Doctrine\ORM\EntityManager;

class TaskServiceProvider implements TaskServiceProviderInterface
{
    public function __construct(private readonly EntityManager $em) {}

    public function createTask(User $user, array $data): Task
    {
        $task = new Task($user, $data);
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    public function getAllTasks(int $id): array
    {
        return $this->em->getRepository(Task::class)->findBy([
            'user' => $id,
            'deletedAt' => null
        ]);
    }

    public function getTask(int $id, int $userId): Task
    {
        $task = $this->em->getRepository(Task::class)->findOneBy([
            'id' => $id,
            'user' => $userId,
            'deletedAt' => null
        ]);

        if (!$task) {
            throw new NotFoundException('task with the current id: ' . $id . ' is not found', 404);
        }

        return $task;
    }

    public function deleteTask(int $id, int $userId): bool
    {
        $task = $this->getTask($id, $userId);

        $task->setDeletedAt(new \DateTime());

        $this->em->flush();
        return true;
    }

    public function updateTask(int $id, int $userId, array $data): Task
    {
        $task = $this->getTask($id, $userId);

        foreach ($data as $key => $value) {

            $method = 'set' .  ucwords($key);


                if (method_exists($task, $method)) {
                    $task->$method($value);
                }
            
        }

        $this->em->flush();
        return $task;
    }
}
