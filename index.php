<?php

include_once "config.php";

include_once DIR_VIEWS . "View.php";

echo View::render_template("page", ["title" => "Acasa", "content" => ""]);
