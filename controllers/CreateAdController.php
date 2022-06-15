<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CONTROLLERS . "JWT.php";
include_once DIR_MODELS . "AnnouncementModel.php";
include_once DIR_MODELS . "BuildingModel.php";

class CreateAdController extends Controller
{
    private $types = ["Apartament", "Casă", "Office"];
    private $ap_types = [
        "Nespecificat", "Decomandat", "Nedecomandat", "Semidecomandat", "Circular",
    ];

    public function __construct()
    {
        $this->register_middleware(new AuthMiddleware(['create_ad']));
    }

    public function create_ad(Request $request)
    {
        $announcement_model = new AnnouncementModel();
        $building_model = new BuildingModel();

        if ($request->is_post()) {

            $temp = $request->get_body();
            $temp['ACCOUNT_ID'] = $request->get_body()['ACCOUNT_ID'] = json_decode(JWT::get_jwt_payload($_COOKIE['user']))->id; // adds account_id value to the array before creating the AnnouncementModel

            // Valori hardcodate pana e gata filterul
            $temp['TRANSACTION_TYPE'] = 'inchiriat';
            $temp['IS_LAND'] = 0;

            if ($temp['TYPE'] == 0 || $temp['TYPE'] == 4) { // no type or land
                $temp['IS_LAND'] = 1;
            }

            $announcement_model->load($temp);

            $no_errors_announcement = $announcement_model->validate();
            $no_errors_building = true;

            if ($announcement_model->get_data()['IS_LAND']['value'] == 0) {
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

            if ($no_errors_announcement && $no_errors_building) {
                $data_mapper = new AnnouncementDM();
                if ($data_mapper->check_existence_title($announcement_model->get_data()['TITLE']['value'],      $announcement_model->get_data()['ACCOUNT_ID']['value']) != 0) {
                    $announcement_model->errors['TITLE'] = "Titlu deja folosit";
                } else {
                    if (isset($_COOKIE['user']) && JWT::is_jwt_valid($_COOKIE['user']) == true) {
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
                        if ($announcement_model->get_data()['IS_LAND']['value'] == 0) {

                            $temp['ANNOUNCEMENT_ID'] = $announcement_id;
                            $building_model->load($temp);
                            $data_mapper = new BuildingDM();
                            $data_mapper->create_building($building_model->get_data());
                        }
                    } else {
                        header('Location: /login');
                        die();
                    }
                }
            }
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['announcement_model' => $announcement_model, 'building_model' => $building_model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAdPage")
            );
        } else {
            return $this->render(
                "Anunț",
                Renderer::render_template("create-ad/create-ad", ['announcement_model' => $announcement_model, 'building_model' => $building_model]),
                Renderer::render_style("form")->add("icon")->add("create-ad"),
                Renderer::render_script("filter")->add("createAdPage")
            );
        }
    }
}
