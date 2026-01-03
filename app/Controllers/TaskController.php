<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\TaskServiceProviderInterface;
use App\Contracts\UserProviderServiceInterface;
use App\ResponseFormatter;
use App\Validators\CreateRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TaskController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly UserProviderServiceInterface $userProvider,
        private readonly TaskServiceProviderInterface $taskServiceProvider,
        private readonly ResponseFormatter $responseFormatter
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->taskServiceProvider->getAllTasks($request->getAttribute('user')['sub']);

        //$tasks = $this->formatter->asJson($tasks);
        $response->getBody()->write(json_encode($tasks));

       return  $this->responseFormatter->response($response, 200);
    }

    public function show(Request $request, Response $response, array $args) : Response
    {
        $task = $this->taskServiceProvider->getTask((int) $args['id'],
         $request->getAttribute('user')['sub']);
        $response->getBody()->write(json_encode($task));
      return  $this->responseFormatter->response($response, 200);

    }

    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(CreateRequestValidator::class)->validate(
            $request->getParsedBody() ?? []
        );
        $user = $this->userProvider->getUserById($request->getAttribute('user')['sub']);
        $task = $this->taskServiceProvider->createTask($user, $data);

        $response->getBody()->write(json_encode($task));
        return  $this->responseFormatter->response($response, 200);

    }

    public function destroy(Request $request, Response $response, array $args)
    {
             $this->taskServiceProvider->deleteTask((int) $args['id'],
               $request->getAttribute('user')['sub']);
            return  $this->responseFormatter->response($response, 200);
    }

    public function update(Request $request, Response $response, array $args)
    {
       $task = $this->taskServiceProvider->updateTask((int)$args['id'], 
        $request->getAttribute('user')['sub'],
        $request->getParsedBody());
        $response->getBody()->write(json_encode($task));
         return  $this->responseFormatter->response($response, 200);

    }
}
