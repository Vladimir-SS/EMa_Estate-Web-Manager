<?php
include_once DIR_VIEWS . "View.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

if (isset($_COOKIE['user'])) {
    header('Location: /home');
    die();
} else {
    echo View::render_template("Page", [
        "title" => "Login",
        "content" => View::render_template("login/login"),
        "styles" => View::render_style("form")->add("icon")->add("login-register"),
        "scripts" => ""
    ]);
}
