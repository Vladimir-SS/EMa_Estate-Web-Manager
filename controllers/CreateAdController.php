<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";
include_once DIR_MODELS . "AnnouncementModel.php";

class CreateAdController extends Controller
{
    public function __construct()
    {
        //$this->register_middleware(new AuthMiddleware(['create_ad']));
    }

    public function create_ad(Request $request)
    {
        $model = new AnnouncementModel();

        if ($request->is_post()) {
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAdPage")
            );
        } else {
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAdPage")
            );
        }
    }
}
