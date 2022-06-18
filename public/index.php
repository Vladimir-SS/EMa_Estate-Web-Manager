<?php
include_once "../file_config.php";

include_once DIR_CORE . "Application.php";
include_once DIR_CONTROLLERS . "PageController.php";
include_once DIR_CONTROLLERS . "AuthController.php";
include_once DIR_CONTROLLERS . "CreateAdController.php";
include_once DIR_CONTROLLERS . "ProfileController.php";
include_once DIR_CONTROLLERS . "ItemPageController.php";
include_once DIR_CONTROLLERS . "api/ItemsController.php";
include_once DIR_CONTROLLERS . "api/ImageController.php";
include_once DIR_CONTROLLERS . "api/ProfileDataController.php";

$app = new Application();

$app->router
    ->get("/", [PageController::class, 'handle_home'])
    ->get("/home", [PageController::class, 'handle_home'])
    ->get("/search", [PageController::class, 'handle_search'])
    ->get("/profile", [ProfileController::class, 'profile'])
    ->post("/profile", [ProfileController::class, 'profile'])
    ->get("/create-ad", [CreateAdController::class, 'create_ad'])
    ->post("/create-ad", [CreateAdController::class, 'create_ad'])
    ->get("/register", [AuthController::class, 'register'])
    ->get("/login", [AuthController::class, 'login'])
    ->post("/register", [AuthController::class, 'register'])
    ->post("/login", [AuthController::class, 'login'])
    ->get("/item", [ItemPageController::class, 'generate_item_page'])
    ->get("/api/items", [ItemsController::class, 'get_items'])
    ->get("/api/items/near", [ItemsController::class, 'get_close_located_items'])
    ->get("/api/item", [ItemsController::class, 'get_item'])
    ->get("/api/items/filter", [ItemsController::class, 'get_filtered_items'])
    ->get("/api/items/image", [ImageController::class, 'get_image'])
    ->get("/api/profile/image", [ImageController::class, 'get_profile_image'])
    ->get("/api/profile/items", [ProfileDataController::class, 'get_profile_items'])
    ->get("/api/profile", [ProfileDataController::class, 'get_profile_data'])
    ->delete("/profile", [ProfileController::class, 'delete_item']);

$app->run();
