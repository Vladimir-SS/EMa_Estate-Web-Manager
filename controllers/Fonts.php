<?php
include_once DIR_CORE . "Application.php";

$file_name = Application::$app->router->get_file_name();

Response::file_response(DIR_VIEWS . 'fonts/' . $file_name);
