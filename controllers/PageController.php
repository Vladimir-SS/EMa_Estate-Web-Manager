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
            Renderer::render_scripts("filter", "filterPage", "Item", "homePage")
        );
    }

    public function handle_search()
    {
        return $this->render(
            "Caută anunțuri",
            Renderer::render_content("search/search"),
            Renderer::render_styles("icon", "item", "search") .
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css">',
            // Renderer::render_script("filter")->add("filterPage")->add("Item")->add("getItems")->add("searchPage")
            '<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>' .
                Renderer::render_scripts("filter", "filterPage", "Item", "ProximityMapHandler", "searchPage")

        );
    }
}
