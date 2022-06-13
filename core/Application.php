<?php

include_once DIR_VIEWS . "View.php";
include_once DIR_CORE . "Router.php";

class Application
{
    public static Application $app;
    public Router $router;
    public View $view;
    public ?Controller $controller = null;

    public static function isGuest()
    {
        if (isset($_COOKIE['user']) && JWT::is_jwt_valid($_COOKIE['user']) == true)
            return false;
        return true;
    }

    public function __construct()
    {
        $this->router = new Router(new Request(), new Response());
        $this->view = new View();
        self::$app = $this;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            $this->view->set_content(Renderer::render_template("_error", ['exception' => $e]));
            $this->view->set_title("Error");
            $this->view->render();
            // echo Renderer::render_template("Page", [
            //     "title" => "Pagină inexistentă",
            //     "content" => Renderer::render_template("_error", ['exception' => $e]),
            //     "styles" => "",
            //     "scripts" => ""
            // ]);
        }
    }
}
