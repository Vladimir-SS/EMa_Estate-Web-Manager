<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";

class PageController extends Controller
{
    public function handle_home()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }

        // echo Renderer::render_template("Page", [
        //     "title" => "Acasă",
        //     "content" => Renderer::render_content("home/Filter")->add("home/items"),
        //     "styles" => Renderer::render_style("icon")->add("home")->add("item"),
        //     "scripts" => Renderer::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        // ]);
        return $this->render(
            "Acasă",
            Renderer::render_content("home/Filter")->add("home/items"),
            Renderer::render_style("icon")->add("home")->add("item"),
            Renderer::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        );
    }

    public function handle_search()
    {
        if (isset($file_name)) {
            include DIR_CONTROLLERS . "RootFiles.php";
        }

        // echo Renderer::render_template("Page", [
        //     "title" => "Caută anunțuri",
        //     "content" => Renderer::render_content("search/search"),
        //     "styles" => Renderer::render_style("icon")->add("item")->add("search"),
        //     "scripts" => Renderer::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        // ]);

        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_style("icon")->add("item")->add("search"),
            Renderer::render_script("FilterOptionHandler")->add("FilterOption")->add("filter")
        );
    }
}
