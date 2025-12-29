<?php

use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function(App $app){
    $app->add(ValidationExceptionMiddleware::class);
};