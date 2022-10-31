<?php

namespace App\core\middlewares;

use App\core\Application;

class AuthMiddleware extends BaseMiddleware
{
    public array $methods = [];

    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    public function execute()
    {
        if (!Application::$app->session->isLoggedIn()) {
            Application::$app->response->redirect('/login');
            exit;
        }
    }
}
