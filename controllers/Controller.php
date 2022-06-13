<?php

class Controller
{
    public array $middlewares = [];
    public string $action = '';

    public function render(string $title, string $content, string $styles = '', string $scripts = '')
    {
        Application::$app->view->set_title($title);
        Application::$app->view->set_content($content);
        Application::$app->view->set_styles($styles);
        Application::$app->view->set_scripts($scripts);
        Application::$app->view->render();
    }

    public function register_middleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function get_middlewares(): array
    {
        return $this->middlewares;
    }
}
