<?php
include_once DIR_CORE . "middlewares/Middleware.php";
//include_once DIR_CORE . "exceptions/ForbiddenException.php";

class AuthMiddleware extends Middleware
{
    private array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                header('Location: /login');
                die();
                // throw new ForbiddenException();
            }
        }
    }
}
