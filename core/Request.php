<?php

namespace App\core;

class Request extends Validation
{
    public array $routeParams = [];

    /**
     * @return string
     */
    public function getUri(): string
    {
        if (str_contains($_SERVER['REQUEST_URI'], '?')) {
            return substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
        }

        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->getRequestMethod() === 'GET';
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->getRequestMethod() === 'POST';
    }

    /**
     * @param array $routes
     * @return void
     */
    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }
}