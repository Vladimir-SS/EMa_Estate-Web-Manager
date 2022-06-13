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
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }
        return $this->render(
            "Acasă",
            Renderer::render_content("home/Filter")->add("home/items"),
            Renderer::render_style("icon")->add("home")->add("item"),
            Renderer::render_script("filter")->add("filterPage")
        );
    }

    public function handle_search()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }
        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_style("icon")->add("item")->add("search"),
            Renderer::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        );
    }
}
