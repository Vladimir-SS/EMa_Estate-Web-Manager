<?php
include_once DIR_VIEWS . "View.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

echo View::render_template("Page", [
    "title" => "Register",
    "content" => View::render_template("register/register"),
    "styles" => View::render_style("form")->add("icon")->add("login-register"),
    "scripts" => View::render_script("form")
]);
