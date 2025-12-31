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
        private readonly ResponseFormatter $formatter,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly UserProviderServiceInterface $userProvider,
        private readonly TaskServiceProviderInterface $taskServiceProvider,
        private readonly ResponseFormatter $responseFormatter
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->taskServiceProvider->getAllTasks();

        $tasks = $this->formatter->asJson($tasks);
        $response->getBody()->write(json_encode($tasks));

       return  $this->responseFormatter->response($response, 200);
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(CreateRequestValidator::class)->validate(
            $request->getParsedBody() ?? []
        );
        $user = $this->userProvider->getUserById($data['user_id']);
        $task = $this->taskServiceProvider->createTask($user, $data);

        $response->getBody()->write(json_encode($task));
        return  $this->responseFormatter->response($response, 200);

    }
}
