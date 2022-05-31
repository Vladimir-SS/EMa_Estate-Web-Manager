<?php
include_once DIR_VIEWS . "View.php";

echo View::render_template("Page", [
    "title" => "Pagină inexistentă",
    "content" => View::render_content("_404"),
    "styles" => "",
    "scripts" => ""
]);
