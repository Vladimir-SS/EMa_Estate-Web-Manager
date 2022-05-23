<?php
include_once DIR_VIEWS . "View.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

if (!isset($_COOKIE['user'])) {
    header('Location: /login');
    die();
} else {
    echo View::render_template("Page", [
        "title" => "Anunt",
        "content" => View::render_template("create-ad/create-ad"),
        "styles" => View::render_style("form")->add("icon")->add("create-ad"),
        "scripts" => View::render_script("create-ad")->add("FilterOptionHandler")->add("FilterOption")->add("filter")
    ]);
}
