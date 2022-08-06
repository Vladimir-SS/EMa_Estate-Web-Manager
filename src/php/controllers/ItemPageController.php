<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";
include_once DIR_MODELS . "AnnouncementModel.php";
include_once DIR_MODELS . "BuildingModel.php";

class ItemPageController extends Controller
{
    public function __construct()
    {
        $this->register_middleware(new AuthMiddleware(['item']));
    }

    public function generate_item_page(Request $request)
    {
        $announcement_model = new AnnouncementModel();
        $building_model = new BuildingModel();
        return $this->render(
            "Item view",
            Renderer::render_template("item_page/item_page",  ['announcement_model' => $announcement_model, 'building_model' => $building_model]),
            Renderer::render_styles("form", "icon", "item-page") .
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css">',
            '<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>' .
                Renderer::render_scripts("ItemInfo", "itemPage", "ProximityMapHandler")
        );
    }
}
