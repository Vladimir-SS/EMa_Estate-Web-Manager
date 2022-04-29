<?php
class Router
{
    static private array $pages = array(
        "GET" => array(),
        "POST" => array()
    );

    static public function get(String $route, string $page_name)
    {
        Router::$pages["GET"][$route] = $page_name;
    }

    static private function get_path(): String
    {
        $path = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, '?');

        if ($position === false)
            return $path;

        return substr($path, 0, $position);
    }

    static public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $pages_method = Router::$pages[$method];

        $path = Router::get_path();
        if (!array_key_exists($path, $pages_method))
            $path = "/404";

        $file_name = $pages_method[$path];

        include_once DIR_CONTROLLERS . $file_name . ".php";
    }

    static public function get_route() //!! delete
    {
        return Router::$pages;
    }
}
