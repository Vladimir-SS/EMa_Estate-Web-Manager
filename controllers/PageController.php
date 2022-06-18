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
        return $this->render(
            "Acasă",
            Renderer::render_content("home/Filter", "home/items", "footer"),
            Renderer::render_styles("icon", "home", "item"),
            Renderer::render_scripts("filter", "filterPage", "Item", "homePage")
        );
    }

    public function handle_search()
    {
        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_styles("icon", "item", "search"),
            Renderer::render_scripts("filter", "filterPage", "Item", "searchPage")
        );
    }
}
