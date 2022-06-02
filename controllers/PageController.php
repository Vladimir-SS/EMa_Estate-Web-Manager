<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";

class PageController extends Controller
{
    public static function handle_home()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }

        echo View::render_template("Page", [
            "title" => "Acasă",
            "content" => View::render_content("home/Filter")->add("home/items"),
            "styles" => View::render_style("icon")->add("home")->add("item"),
            "scripts" => View::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        ]);
    }

    public static function handle_search()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }

        echo View::render_template("Page", [
            "title" => "Caută anunțuri",
            "content" => View::render_content("search/search"),
            "styles" => View::render_style("icon")->add("item")->add("search"),
            "scripts" => View::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        ]);
    }

    public static function handle_create_ad()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }

        if (isset($_COOKIE['user'])) {
            if (JWT::is_jwt_valid($_COOKIE['user']) == true) {
                echo View::render_template("Page", [
                    "title" => "Anunț",
                    "content" => View::render_content("create-ad/create-ad"),
                    "styles" => View::render_style("form")->add("icon")->add("create-ad"),
                    "scripts" => View::render_script("create-ad")->add("FilterOptionHandler")->add("FilterOption")->add("filter")
                ]);
            } else {
                header('Location: /login');
                die();
            }
        } else {
            header('Location: /login');
            die();
        }
    }
}
