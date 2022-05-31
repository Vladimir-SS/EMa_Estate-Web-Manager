<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";
include_once DIR_MODELS . "AnnouncementModel.php";

class CreateAdController extends Controller
{

    public static function create_ad(Request $request)
    {
        if ($request->is_post()) {

            $temp_var_for_testing = $request->get_body();
            $temp_var_for_testing['account_id'] = $request->get_body()['account_id'] = json_decode(get_jwt_payload($_COOKIE['user']))->id; // adds account_id value to the array before creating the AnnouncementModel

            // Valori hardcoadate pana e gata filterul
            $temp_var_for_testing['price'] = 9999;
            $temp_var_for_testing['surface'] = 99;
            $temp_var_for_testing['transaction_type'] = 'inchiriat';

            // echo "<pre>";
            // var_dump($temp_var_for_testing);
            // echo "</pre>";

            $model = new AnnouncementModel();
            $model->load($temp_var_for_testing); // should be $request->get_body() when the filter is done
            $result = $model->validate();

            if (empty($result)) {
                if (is_jwt_valid($_COOKIE['user']) == true) {
                    $account_data = json_decode(get_jwt_payload($_COOKIE['user']));
                    echo "<pre>";
                    var_dump($account_data->id);
                    echo "</pre>";
                    $data_mapper = new AnnouncementDM();
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
                }
            } else {
                header("Location: /create-ad");
                die();
            }
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            if (isset($_COOKIE['user'])) {
                if (is_jwt_valid($_COOKIE['user']) == true) {
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
}
