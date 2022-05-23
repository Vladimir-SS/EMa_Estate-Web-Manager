<?php
include_once "file_config.php";
include_once DIR_ROUTER . "Router.php";

$router = new Router();

$router
    ->get("/", "Home")
    ->get("/home", "Home")
    ->get("/login", "Login")
    ->get("/register", "Register")
    ->get("/fonts", "Fonts")
    ->get("/404", "Error")
    ->get("/api/properties", "api/GetProperties")
    ->get("/create-ad", "CreateAd")
    ->post("/create-ad", "form/CreateAdReq")
    ->post("/register", "form/RegisterReq")
    ->post("/login", "form/LoginReq")
    ->run();

// echo "<pre>";
// var_dump(Router::get_route());
// echo "</pre>";
