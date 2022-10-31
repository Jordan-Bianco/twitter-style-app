<?php

namespace App\core\middlewares;

use App\core\Application;

class GuestMiddleware extends BaseMiddleware
{
    public array $methods = [];

    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    public function execute()
    {
        if (Application::$app->session->isLoggedIn()) {
            Application::$app->response->redirect('/tweets');
            exit;
        }
    }
}
