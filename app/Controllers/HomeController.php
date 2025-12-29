<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    public function index(Request $request, Response $response): Response{
            echo 'Home';
            return $response;
        }
}
