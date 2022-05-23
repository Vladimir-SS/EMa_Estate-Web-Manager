<?php
include_once DIR_VIEWS . "View.php";

if (isset($file_name)) {
    include DIR_CONTROLLERS . "RootFiles.php";
}

echo View::render_template("Page", [
    "title" => "Acasă",
    "content" => View::render_content("home/Filter")->add("home/items"),
    "styles" => View::render_style("icon")->add("home")->add("item"),
    "scripts" => View::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
]);
