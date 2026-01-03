<?php

use App\Controllers\AuthController;
use App\Controllers\TaskController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
   

    $app->group('/api/tasks', function (RouteCollectorProxy $task) {
        $task->get('', [TaskController::class, 'index']);
        $task->get('/{id}', [TaskController::class, 'show']);
        $task->post('', [TaskController::class, 'store']);
        $task->delete('/{id}', [TaskController::class, 'destroy']);
        $task->patch('/{id}', [TaskController::class, 'update']);
    })->add(AuthMiddleware::class);

     $app->post('/api/register', [AuthController::class, 'register']);
    $app->post('/api/login', [AuthController::class, 'Login']);
};

