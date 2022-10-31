<?php

namespace App\core\middlewares;

use App\core\Application;

class AdminMiddleware extends BaseMiddleware
{
    public array $methods = [];

    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    public function execute()
    {
        /** wip - controllo se admin in base all'email */
        if (Application::$app->session->get('user')['username'] !== 'admin@admin.com') {
            Application::$app->response->redirect('/');
            exit;
        }
    }
}
