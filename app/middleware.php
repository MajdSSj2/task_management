<?php

use App\Middleware\CORS;
use App\Middleware\ErrorMiddleware ;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function(App $app){
    $app->add(CORS::class);
    $app->add(ValidationExceptionMiddleware::class);

    $app->add(ErrorMiddleware::class);
};