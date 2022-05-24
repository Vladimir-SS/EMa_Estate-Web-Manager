<?php
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

if (isset($_COOKIE['user'])) {
    if (is_jwt_valid($_COOKIE['user']) == true) {
        header('Location: /home');
        die();
    }
} else {
    echo View::render_template("Page", [
        "title" => "Login",
        "content" => View::render_content("login/login"),
        "styles" => View::render_style("form")->add("icon")->add("login-register"),
        "scripts" => ""
    ]);
}
