<?php

use App\Middleware\CORS;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function(App $app){
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(CORS::class);
};