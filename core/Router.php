<?php

namespace App\core;

use App\controllers\Controller;
use App\core\exceptions\NotFoundException;

class Router
{
    public array $routes = [];
    public Renderer $renderer;
    public Request $request;
    public Response $response;

    public function __construct(Renderer $renderer, Request $request, Response $response)
    {
        $this->renderer = $renderer;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param string $path
     * @param mixed  $callback
     * @return void
     */
    public function get(string $path, mixed $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function post(string $path, mixed $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * @param string  $uri
     * @param string $requestMethod
     * @return void
     * @throws NotFoundException
     */
    public function direct(string $uri, string $requestMethod): void
    {
        $callback = $this->routes[$requestMethod][$uri] ?? false;

        /** Se la rotta nell'uri non è presente nell'array routes, cerco se ci sono parametri  */
        if (!$callback) {

            $callback = $this->parseUrl($uri, $requestMethod);

            if (!$callback) {
                throw new NotFoundException('Route not found');
            }
        }

        if (is_callable($callback)) {
            call_user_func($callback);
        }

        if (is_string($callback)) {
            $this->renderer->render($callback);
        }

        if (is_array($callback)) {
            $this->callAction(...$callback);
        }
    }

    /**
     * @param string $uri
     * @param string $requestMethod
     * @return mixed
     */
    private function parseUrl(string $uri, string $requestMethod): mixed
    {
        foreach ($this->routes[$requestMethod] as $path => $callback) {
            $placeholderParams = [];

            $path = trim($path, '/');

            // Cerco nelle rotte, tutte quelle che hanno un placeholder e le salvo nell'array matches
            preg_match_all('[\{(.*?)\}]', $path, $matches);

            $placeholderParams = $matches[1];

            // Convertire il path di ogni rotta in una stringa, sostituendo tutto ciò che c'è tra parantesi con la regex (\w+)
            $regexRoute = "@^" . preg_replace('[\{(.*?)\}]', '(\w+)', $path) . '$@';

            // Bisogna prendere il valore dell'url e sostituirlo alla regex
            if (preg_match_all($regexRoute, trim($uri, '/'), $vmatches)) {
                $values = [];

                for ($i = 1; $i < count($vmatches); $i++) {
                    $values[] = $vmatches[$i][0];
                }

                $routeParams = array_combine($placeholderParams, $values);

                $this->request->setRouteParams($routeParams);

                return $callback;
            }
        }

        return false;
    }

    /**
     * @param string $controller
     * @param string $action
     * @return void
     * @throws NotFoundException
     */
    private function callAction(string $controller, string $action): void
    {
        if (!class_exists($controller)) {
            throw new NotFoundException("No controller $controller was found");
        }

        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new NotFoundException("No method $action on the controller " . get_class($controller));
        }

        $this->checkMiddleware($controller, $action);

        $controller->$action($this->request);
    }

    /**
     * @param Controller $controller
     * @param string $action
     * @return void
     */
    private function checkMiddleware(Controller $controller, string $action): void
    {
        /** Check for middleware */
        foreach ($controller->middlewares as $middleware) {

            if (in_array($action, $middleware->methods)) {
                $middleware->execute();
            }
        }

    }
}