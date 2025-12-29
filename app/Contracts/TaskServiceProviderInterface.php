<?php

namespace App\Contracts;

use App\Entity\Task;
use App\Entity\User;

interface TaskServiceProviderInterface
{
    public function createTask(User $user, array $data) : Task;
    public function getAllTasks() : array ;
}
