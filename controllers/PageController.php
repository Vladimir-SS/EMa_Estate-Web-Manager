<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";

class PageController extends Controller
{

    public function __construct()
    {
    }

    public function handle_home()
    {
        $file_name = Application::$app->router->get_file_name();
        if ($file_name !== '') {
            Response::file_response(DIR_BASE . 'resources/' . $file_name);
        }
        return $this->render(
            "Acasă",
            Renderer::render_content("home/Filter")->add("home/items"),
            Renderer::render_style("icon")->add("home")->add("item"),
            Renderer::render_script("filter")->add("filterPage")->add("Item")->add("getItems")
        );
    }

    public function handle_search()
    {
        $file_name = Application::$app->router->get_file_name();
        if ($file_name !== '') {
            Response::file_response(DIR_BASE . 'resources/' . $file_name);
        }

        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_style("icon")->add("item")->add("search"),
            Renderer::render_script("filter")->add("filterPage")->add("Item")->add("getItems")
        );
    }
}
