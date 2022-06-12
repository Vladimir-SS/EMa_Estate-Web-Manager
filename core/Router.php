<?php

include_once DIR_CORE . "Request.php";
include_once DIR_CORE . "Response.php";
include_once DIR_CORE . "exceptions/NotFoundException.php";

class Router
{
    private array $routes;
    private Request $request;
    private Response $reponse;

    public function __construct(Request $request, Response $reponse)
    {
        $this->request = $request;
        $this->response = $reponse;
    }

    static private function split_path($path): array
    {
        preg_match('/^(?<path>[^.\s]+)?(?:\/(?<file_name>.+\..+))?$/', $path, $matches);
        for ($i = 0; $i < 3; ++$i)
            unset($matches[$i]);

        if ($matches["path"] === "")
            $matches["path"] = "/";

        return $matches;
    }

    public function get(String $route, $callback): Router
    {
        $this->routes["get"][$route] = $callback;

        return $this;
    }

    public function post(String $route, $callback): Router
    {
        $this->routes["post"][$route] = $callback;

        return $this;
    }

    public function resolve()
    {
        $path = $this->request->get_path();
        extract(self::split_path($this->request->get_path())); // ??

        $method = $this->request->get_method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            include_once DIR_CONTROLLERS . $callback . ".php";
        } else if (is_array($callback)) {

            $controller = new $callback[0]();

            Application::$app->controller = $controller;
            Application::$app->controller->action = $callback[1];

            $callback[0] = $controller;

            foreach ($controller->get_middlewares() as $middleware) {
                $middleware->execute();
            }

            call_user_func($callback, $this->request);
        }
    }
}
