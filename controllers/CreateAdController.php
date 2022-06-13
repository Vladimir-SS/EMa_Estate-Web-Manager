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

            $temp_var_for_testing = $request->get_body();
            $temp_var_for_testing['account_id'] = $request->get_body()['account_id'] = json_decode(JWT::get_jwt_payload($_COOKIE['user']))->id; // adds account_id value to the array before creating the AnnouncementModel

            // Valori hardcodate pana e gata filterul
            $temp_var_for_testing['price'] = 9999;
            $temp_var_for_testing['surface'] = 99;
            $temp_var_for_testing['transaction_type'] = 'inchiriat';

            $model->load($temp_var_for_testing); // should be $request->get_body() when the filter is done
            $no_errors = $model->validate();

            if ($no_errors) {
                $data_mapper = new AnnouncementDM();
                if ($data_mapper->check_existence_title($model->get_data()['title']['value'])) {
                    $model->errors['title'] = "Titlu deja folosit";
                } else {
                    if (isset($_COOKIE['user']) && JWT::is_jwt_valid($_COOKIE['user']) == true) {
                        $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
                        $data_mapper->create_announcement($model->get_data());
                        $id = $data_mapper->find_id_by_account_id_and_title($account_data->id, $request->get_body()['title']);

                        foreach ($_FILES["images"]["error"] as $key => $error) {

                            if ($error == UPLOAD_ERR_OK) {

                                if (!empty($_FILES["images"]["name"][$key])) {

                                    $name = $_FILES["images"]["name"][$key];
                                    $type = $_FILES["images"]["type"][$key];
                                    $blob = addslashes(file_get_contents($_FILES["images"]["tmp_name"][$key]));
                                    $data_mapper->add_image($id, $blob, $name, $type);
                                }
                            }
                        }
                    } else {
                        header('Location: /login');
                        die();
                    }
                }
            }
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAd")
            );
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAd")
            );
        }
    }
}
