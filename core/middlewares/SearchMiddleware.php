<?php

namespace App\core\middlewares;

use App\core\Application;

class SearchMiddleware extends BaseMiddleware
{
    public array $methods = [];

    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    public function execute()
    {
        if (!str_contains($_SERVER['REQUEST_URI'], '?search=')) {
            Application::$app->response->redirect('/tweets');
            exit;
        }
    }
}
