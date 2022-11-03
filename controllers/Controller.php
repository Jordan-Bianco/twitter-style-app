<?php

namespace App\controllers;

use App\core\Application;
use App\core\middlewares\BaseMiddleware;

class Controller
{
    protected Application $app;
    public array $middlewares = [];

    public function __construct()
    {
        $this->app = Application::$app;
    }

    /**
     * @param BaseMiddleware $middleware
     * @return void
     */
    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        array_push($this->middlewares, $middleware);
    }

    /**
     * Check that the ID of the logged in user is the same ID as the author of the resource you intend to modify.
     * 
     * @param int $author
     * @return bool
     */
    public function isAuthorized(int $authorId): bool
    {
        return (int) $this->app->session->authId() === (int) $authorId;
    }

    /**
     * @param string $name
     * @param array  $data
     * @return void
     */
    public function view(string $name, array $data = []): void
    {
        $this->app->renderer->render($name, $data);
    }
}
