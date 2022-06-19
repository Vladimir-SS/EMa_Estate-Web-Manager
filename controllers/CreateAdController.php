<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";
include_once DIR_MODELS . "AnnouncementModel.php";
include_once DIR_MODELS . "BuildingModel.php";

class CreateAdController extends Controller
{
    private $types = ["land", "apartment", "house", "office", "land"];
    private $ap_types = ["", "detached", "semi-detached", "non-detached", "circular", "open-space"];
    private $transaction_types = ["rent", "rent", "sell"];

    public function __construct()
    {
        $this->register_middleware(new AuthMiddleware(['create_ad']));
    }

    public function create_ad(Request $request)
    {
        $announcement_model = new AnnouncementModel();
        $building_model = new BuildingModel();
        $image_error = false;

        if ($request->is_post()) {

            $temp = $request->get_body();
            $temp['ACCOUNT_ID'] = $request->get_body()['ACCOUNT_ID'] = json_decode(JWT::get_jwt_payload($_COOKIE['user']))->id; // adds account_id value to the array before creating the AnnouncementModel

            $temp['TRANSACTION_TYPE'] = $this->transaction_types[$temp['TRANSACTION_TYPE']];
            $temp['TYPE'] = $this->types[$temp['TYPE']];

            $announcement_model->load($temp);

            $no_errors_announcement = $announcement_model->validate();

            $no_errors_building = true;

            if ($announcement_model->get_data()['TYPE']['value'] === $this->types[1]) {
                $temp = $request->get_body();
                $temp['TYPE'] = $this->types[$temp['TYPE']];
                if (isset($temp['AP_TYPE'])) {
                    $temp['AP_TYPE'] = $this->ap_types[$temp['AP_TYPE']];
                } else {
                    $temp['AP_TYPE'] = $this->ap_types[0];
                }
                $building_model->load($temp);
                $no_errors_building = $building_model->validate();
            }

            if (empty($_FILES)) {
                $image_error = "Anunțul trebuie să aibă măcar o imagine";
            }

            if ($no_errors_announcement && $no_errors_building && $image_error === false) {
                if (empty($temp['LAT']) || empty($temp['LON'])) {
                    $announcement_model->errors['ADDRESS'] = "Adresă invalidă";
                } else {
                    $data_mapper = new AnnouncementDM();
                    if ($data_mapper->check_existence_title($announcement_model->get_data()['TITLE']['value'],      $announcement_model->get_data()['ACCOUNT_ID']['value']) != 0) {
                        $announcement_model->errors['TITLE'] = "Titlu deja folosit";
                    } else {
                        $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
                        $announcement_id = $data_mapper->create_announcement($announcement_model->get_data());
                        $id = $data_mapper->find_id_by_account_id_and_title($account_data->id, $request->get_body()['TITLE']);

                        if (!empty($_FILES)) {
                            foreach ($_FILES as $key => $value) {
                                if ($value['error'] == UPLOAD_ERR_OK) {

                                    if (!empty($value["name"])) {
                                        $name = $value["name"];
                                        $type = $value["type"];
                                        $blob = file_get_contents($value["tmp_name"]);

                                        $blob = base64_encode($blob);
                                        $data_mapper->add_image($id, $blob, $name, $type);
                                    }
                                }
                            }
                        }
                        if ($announcement_model->get_data()['TYPE']['value'] !== $this->types[4]) {
                            $temp['ANNOUNCEMENT_ID'] = $announcement_id;
                            $building_model->load($temp);
                            $data_mapper = new BuildingDM();
                            $data_mapper->create_building($building_model->get_data());
                        }
                        header("Location: /item?id=$id");
                        die();
                    }
                }
            }
        }

        return $this->render(
            "Creare anunț",
            Renderer::render_template("create-ad/create-ad", ['announcement_model' => $announcement_model, 'building_model' => $building_model, 'image_error' => $image_error]),
            Renderer::render_styles("form", "icon", "create-ad") .
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css">',
            '<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>' .
                Renderer::render_scripts("filter", "createAdPage")
        );
    }
}
