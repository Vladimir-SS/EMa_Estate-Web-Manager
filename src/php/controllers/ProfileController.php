<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_MODELS . "ProfileModel.php";
include_once DIR_CORE . "exceptions/UnauthorizedException.php";

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->register_middleware(new AuthMiddleware(['profile']));
    }

    public function profile(Request $request)
    {
        $model = new ProfileModel();
        $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
        $id = $account_data->id;
        if ($request->is_post()) {
            $services = new AccountService();
            $data_mapper = new AccountDM();
            $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
            if (
                isset($request->get_body()['PASSWORD'])
                && isset($request->get_body()['CONFIRM_PASSWORD'])
                && $request->get_body()['PASSWORD'] !== $request->get_body()['CONFIRM_PASSWORD']
            ) {
                $model->errors['CONFIRM_PASSWORD'] = "Parolele nu coincid!";
            }

            $model->load($request->get_body());
            $no_errors = $model->validate();
            if ($no_errors) {

                if ($data_mapper->check_existence_email($model->get_data()['EMAIL']['value'], $account_data->id) > 0) {
                    $no_errors = false;
                    $model->errors['EMAIL'] = "Email deja folosit";
                }
                if ($data_mapper->check_existence_phone($model->get_data()['PHONE']['value'], $account_data->id) > 0) {
                    $no_errors = false;
                    $model->errors['PHONE'] = "Număr de telefon deja folosit";
                }
                if ($no_errors) {

                    $data = $model->get_data();
                    $salt = $data_mapper->get_salt_by_id($account_data->id);
                    $passwordSP = $data_mapper->get_password_by_id($account_data->id);
                    if ($services->password_check($model->data['OLD_PASSWORD'], $salt, $passwordSP)) {
                        unset($data['CONFIRM_PASSWORD']);
                        unset($data['OLD_PASSWORD']);
                        unset($data['CREATED_AT']);
                        if (!empty($data['PASSWORD']['value'])) {
                            $data['PASSWORD']['value'] = $services->generate_hash($services->add_salt_and_pepper($data['PASSWORD']['value'], $salt));
                        } else {
                            unset($data['PASSWORD']);
                        }

                        $data_mapper->update_account_data($account_data->id, $data);
                        if (!empty($_FILES)) {

                            if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                                $blob = file_get_contents($_FILES['image']["tmp_name"]);
                                $blob = base64_encode($blob);
                                $data_mapper->update_account_image($account_data->id, $blob);
                            }
                        }
                    } else {
                        $model->errors['OLD_PASSWORD'] = "Parolă greșită";
                    }
                }
            }
            if (($data = $data_mapper->get_data_by_id($account_data->id)) != false) {
                $model->load($data);
            } else {
                throw new UnauthorizedException();
            }
            $model->load($data);
            if (!empty($_FILES)) {
                $image = file_get_contents($_FILES['image']["tmp_name"]);
                $image = base64_encode($image);
            }
            return $this->render(
                "Profil",
                Renderer::render_template("profile/profile", ['model' => $model, 'id' => $id]),
                Renderer::render_styles("form", "icon", "item", "search", "profile"),
                Renderer::render_scripts("avatar-loader", "Item", "profilePage")
            );
        } else {
            $data_mapper = new AccountDM();
            $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
            if (($data = $data_mapper->get_data_by_id($account_data->id)) != false) {
                $model->load($data);
            } else {
                throw new UnauthorizedException();
            }
            return $this->render(
                "Profil",
                Renderer::render_template("profile/profile", ['model' => $model, 'id' => $id]),
                Renderer::render_styles("form", "icon", "item", "search", "profile"),
                Renderer::render_scripts("avatar-loader", "Item", "profilePage")
            );
        }
    }

    public function delete_item(Request $request)
    {
        $data_mapper = new AnnouncementDM();
        if (!Application::isGuest()) {
            $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
            if (isset($request->get_body()['announcement_id'])) {
                $result = $data_mapper->delete_announcement_of_id($account_data->id, $request->get_body()['announcement_id']);
                echo json_encode($result);
                die();
            }
        }
        echo json_encode("false");
    }
}
