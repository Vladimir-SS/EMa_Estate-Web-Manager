<?php
include_once "file_config.php";
include_once DIR_CORE . "Application.php";
include_once DIR_CONTROLLERS . "PageController.php";
include_once DIR_CONTROLLERS . "AuthController.php";
include_once DIR_CONTROLLERS . "CreateAdController.php";


//$router = new Router(new Request(), new Response());
$app = new Application();

$app->router
    ->get("/", [PageController::class, 'handle_home'])
    ->get("/home", [PageController::class, 'handle_home'])
    ->get("/search", [PageController::class, 'handle_search'])
    ->get("/fonts", "Fonts")
    ->get("/create-ad", [CreateAdController::class, 'create_ad'])
    ->post("/create-ad", [CreateAdController::class, 'create_ad'])
    ->get("/register", [AuthController::class, 'register'])
    ->get("/login", [AuthController::class, 'login'])
    ->post("/register", [AuthController::class, 'register'])
    ->post("/login", [AuthController::class, 'login']);

$app->run();
