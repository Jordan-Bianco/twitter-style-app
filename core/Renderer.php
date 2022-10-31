<?php

namespace App\core;

use App\core\exceptions\ViewNotFoundException;

class Renderer
{
    public $title;

    public function __construct()
    {
        $this->title = $_ENV['SITE_NAME'] ?? '';
    }

    /**
     * @param string $name
     * @param array  $data
     * @return void
     */
    public function render(string $name, array $data = []): void
    {
        $viewContent = $this->renderViewContent($name, $data);
        $layoutContent = $this->renderLayoutContent();

        echo str_replace('{{ content }}', $viewContent, $layoutContent);
    }

    /**
     * @return string 
     */
    protected function renderLayoutContent(): string
    {
        ob_start();
        require_once ROOT_PATH . '/views/layouts/main.php';
        return ob_get_clean();
    }

    /**
     * @param string $name
     * @param array  $data
     * @return string
     * @throws ViewNotFoundException
     */
    protected function renderViewContent(string $name, array $data): string
    {
        $viewPath = ROOT_PATH . "/views/$name.view.php";

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException("View '$name' not found.");
        }

        ob_start();
        extract($data);
        require_once $viewPath;
        return ob_get_clean();
    }
}