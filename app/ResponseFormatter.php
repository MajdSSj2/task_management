<?php

namespace App;

use App\Entity\Task;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatter
{
    
    public function asJson(array $tasks, Response $response): Response{
          $data = array_map(fn(Task $t) => [
            'id'          => $t->getId(),
            'title'       => $t->getTitle(),
            'description' => $t->getDescription(),
            'priority'    => $t->getPriority(),
            'done'        => $t->getDone(),
            'user_id'     => $t->getUser()->getId(),
        ], $tasks);

         $response->getBody()->write(json_encode($data));
         return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
