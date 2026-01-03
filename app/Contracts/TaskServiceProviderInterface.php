<?php

namespace App\Contracts;

use App\Entity\Task;
use App\Entity\User;

interface TaskServiceProviderInterface
{
    public function getTask(int $id, int $userId) : Task;
    public function createTask(User $user, array $data) : Task;
    public function getAllTasks(int $userId) : array ;
    public function deleteTask(int $id, int $userId): bool;
    public function updateTask(int $id, int $userId ,array $data): Task;

}
