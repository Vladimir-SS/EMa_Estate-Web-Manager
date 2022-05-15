<?php
class Router
{
    private array $pages;

    public function __construct()
    {
        $this->pages = array(
            "GET" => array(),
            "POST" => array()
        );
    }

    public function get(String $route, string $page_name): Router
    {
        $this->pages["GET"][$route] = $page_name;

        return $this;
    }

    static private function get_path(): String
    {
        $path = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, '?');

        if ($position === false)
            return $path;

        return substr($path, 0, $position);
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

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $pages_method = $this->pages[$method];

        $path = "";
        extract(self::split_path(self::get_path()));

        if (!array_key_exists($path, $pages_method))
            $path = "/404";

        include_once DIR_CONTROLLERS . $pages_method[$path] . ".php";
    }
}
