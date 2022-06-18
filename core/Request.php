<?php
class Request
{

    public function get_path(): String
    {
        $path = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, '?');

        if ($position === false)
            return $path;

        return substr($path, 0, $position);
    }

    public function get_method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function is_get()
    {
        return $this->get_method() == 'get';
    }

    public function is_post()
    {
        return $this->get_method() == 'post';
    }

    public function is_delete()
    {
        return $this->get_method() == 'delete';
    }

    public function get_body()
    {
        $body = [];

        if ($this->get_method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->get_method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->get_method() === 'delete') {
            parse_str(file_get_contents("php://input"), $body);
        }

        return $body;
    }
}
