<?php

use App\Controllers\AuthController;
use App\Controllers\TaskController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->post('/api/register', [AuthController::class, 'register']);
    $app->post('/api/login', [AuthController::class, 'Login']);

    $app->group('/api/tasks', function (RouteCollectorProxy $task) {
        $task->get('', [TaskController::class, 'index']);
        $task->post('', [TaskController::class, 'store']);
    })->add(AuthMiddleware::class);
};
