<?php

namespace App\Controller;

use App\View;

class ErrorController
{
    public function __construct(\PDO $database)
    {
    }

    public function notFound($request, $response)
    {
        $response->notFound();
    }
}