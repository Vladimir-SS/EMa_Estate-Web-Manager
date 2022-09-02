<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";

class PageController extends Controller
{
    public function handle_home()
    {
        return $this->render(
            "Acasă",
            Renderer::render_content("home/Filter", "home/items", "footer"),
            Renderer::render_styles("icon", "home", "item"),
            Renderer::render_scripts("home-page")
        );
    }

    //TODO: delete this
    public function handle_test(){
        return $this->render(
            "Testing",
            Renderer::render_content("testing"),
            '', ''
        );
    }

    public function handle_search()
    {
        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_styles("icon", "item", "search", "ol"),
            Renderer::render_scripts("search-page")

        );
    }
}
