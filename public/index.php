<?php
include_once "file_config.php";
include_once DIR_CORE . "Application.php";
include_once DIR_CONTROLLERS . "PageController.php";
include_once DIR_CONTROLLERS . "AuthController.php";
include_once DIR_CONTROLLERS . "CreateAdController.php";
include_once DIR_CONTROLLERS . "ProfileController.php";
include_once DIR_CONTROLLERS . "api/ItemsController.php";

$app = new Application();

$app->router
    ->get("/", [PageController::class, 'handle_home'])
    ->get("/home", [PageController::class, 'handle_home'])
    ->get("/search", [PageController::class, 'handle_search'])
    ->get("/profile", [ProfileController::class, 'profile'])
    ->post("/profile", [ProfileController::class, 'profile'])
    ->get("/fonts", "Fonts")
    ->get("/create-ad", [CreateAdController::class, 'create_ad'])
    ->post("/create-ad", [CreateAdController::class, 'create_ad'])
    ->get("/register", [AuthController::class, 'register'])
    ->get("/login", [AuthController::class, 'login'])
    ->post("/register", [AuthController::class, 'register'])
    ->post("/login", [AuthController::class, 'login'])
    ->get("/api/items", [ItemsController::class, 'get_items'])
    ->get("/api/items/filter", [ItemsController::class, 'get_filtered_items']);

$app->run();
