<?php
echo View::render_template("Page", [
    "title" => "Acasă",
    "content" => View::render_template("page/Filter"),
    "styles" =>
    View::render_style("icon") .
        View::render_style("home"),
    "scripts" => View::render_script("filter")
]);
