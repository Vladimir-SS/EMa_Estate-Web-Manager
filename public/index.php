<?php
include_once "../config.php";
include_once DIR_ROUTER . "Router.php";

Router::get("/", "Home");
Router::get("/home", "Home");
Router::get("/fonts", "Fonts");
Router::get("/404", "Error");



// echo "<pre>";
// var_dump(Router::get_route());
// echo "</pre>";

Router::run();
