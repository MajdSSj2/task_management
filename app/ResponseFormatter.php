<?php

namespace App;

use App\Entity\Task;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatter
{
    
    public function asJson(array $data): array{
          return array_map(fn(Task $t) => [
            'id'          => $t->getId(),
            'title'       => $t->getTitle(),
            'description' => $t->getDescription(),
            'priority'    => $t->getPriority(),
            'done'        => $t->getDone(),
            'user_id'     => $t->getUser()->getId(),
        ], $data);

      
    }
}
