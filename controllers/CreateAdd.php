<?php
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

if (isset($_COOKIE['user'])) {
    if (is_jwt_valid($_COOKIE['user']) == TRUE) {
        echo View::render_template("Page", [
            "title" => "Anunt",
            "content" => View::render_content("create-ad/create-ad"),
            "styles" => View::render_style("form")->add("icon")->add("create-ad"),
            "scripts" => View::render_script("create-ad")->add("FilterOptionHandler")->add("FilterOption")->add("filter")
        ]);
    } else {
        header('Location: /login');
        die();
    }
}
