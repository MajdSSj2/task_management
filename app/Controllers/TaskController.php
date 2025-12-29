<?php

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\Entity\Task;
use App\Entity\User;
use App\ResponseFormatter;
use App\Validators\CreateTaskRequestValidator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TaskController
{
    public function __construct(private readonly EntityManager $em,
     private readonly ResponseFormatter $formatter,
     private readonly RequestValidatorFactoryInterface $requestValidatorFactory ,

     ) {}

    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->em->getRepository(Task::class)->findAll();

        // Convert Doctrine Objects to json encode it in the response
        $response = $this->formatter->asJson($tasks, $response);
        return $response;
    }

    public function store(Request $request, Response $response): Response
    {  
        $data = $this->requestValidatorFactory->make(CreateTaskRequestValidator::class)->validate(
            $request->getParsedBody() ?? []
        );
        $user = $this->em->getRepository(User::class)->findOneBy(['id'=> 1]);
        $task = new Task($user, $data);
        $this->em->persist($task);
        $this->em->flush();
        return $response;
    }
}
