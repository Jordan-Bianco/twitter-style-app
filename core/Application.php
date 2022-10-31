<?php

namespace App\core;

use App\core\database\Database;
use App\core\database\QueryBuilder;

class Application
{
    public static Application $app;

    public Router $router;
    public Request $request;
    public Response $response;
    public Renderer $renderer;
    public Session $session;
    public Database $db;
    public QueryBuilder $builder;

    public function __construct(Config $config)
    {
        self::$app = $this;

        $this->request = new Request();
        $this->renderer = new Renderer();
        $this->response = new Response();
        $this->session = new Session();

        $this->router = new Router($this->renderer, $this->request, $this->response);

        $this->db = new Database($config->config['database']);
        $this->builder = new QueryBuilder($this->db->pdo);
    }

    public function run()
    {
        try {
            $this->router->direct($this->request->getUri(), $this->request->getRequestMethod());
        } catch (\Exception $e) {

            $this->response->setStatusCode((int) $e->getCode());

            $this->renderer->render('error', ['error' => $e]);

            return;
        }
    }
}