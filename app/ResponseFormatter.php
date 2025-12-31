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
            'due'         => $t->getDue() ? $t->getDue()->format('Y-m-d g:i:s A') : null,
            'done'        => $t->getDone(),
            'createdAt'   => $t->getCreatedAt() ? $t->getCreatedAt()->format('Y-m-d g:i:s A') : null,
            'user_id'     => $t->getUser()->getId(),
        ], $data);
    }

    public function response(Response $response, int $code): Response
    {
        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($code);
    }

}
