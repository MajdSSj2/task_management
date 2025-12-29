<?php

use App\Controllers\HomeController;
use App\Controllers\TaskController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function(App $app){
    $app->group('/api/tasks', function(RouteCollectorProxy $task){
        $task->get('', [TaskController::class, 'index']);
        $task->post('', [TaskController::class, 'store']);
    });
};