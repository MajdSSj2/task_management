<?php

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\TaskServiceProviderInterface;
use App\Contracts\UserProviderServiceInterface;
use App\ResponseFormatter;
use App\Validators\CreateTaskRequestValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TaskController
{
    public function __construct(
     private readonly ResponseFormatter $formatter,
     private readonly RequestValidatorFactoryInterface $requestValidatorFactory ,
     private readonly UserProviderServiceInterface $userProvider,
     private readonly TaskServiceProviderInterface $taskServiceProvider
     ) {}

    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->taskServiceProvider->getAllTasks();

        // Convert Doctrine Objects to json encode it in the response
        $tasks = $this->formatter->asJson($tasks);
        $response->getBody()->write(json_encode($tasks));
         return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);

    }

    public function store(Request $request, Response $response): Response
    {  
        $data = $this->requestValidatorFactory->make(CreateTaskRequestValidator::class)->validate(
            $request->getParsedBody() ?? []
        );
        $user = $this->userProvider->getUserById($data['user_id']);
        $task = $this->taskServiceProvider->createTask($user, $data);
          $response->getBody()->write(json_encode($task));
         return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
