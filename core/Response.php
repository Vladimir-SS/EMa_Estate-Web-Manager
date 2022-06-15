<?php
class Response
{

    // TODO: inlocuita cu json_response, poate idk

    // public static function file_response(string $path): void
    // {
    //     if (!(file_exists($path) && is_file($path))) {
    //         throw new NotFoundException();
    //     }
    //     header('Content-Type: ' . mime_content_type($path));
    //     die(file_get_contents($path));
    // }

    public function status_code(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
    }
}
