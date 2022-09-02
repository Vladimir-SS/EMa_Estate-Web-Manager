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

                if ($data_mapper->check_existence_email($model->get_data()['email'], $account_data->id)) {
                    $no_errors = false;
                    $model->errors['email'] = "Email deja folosit";
                }
                if ($data_mapper->check_existence_phone($model->get_data()['phone'], $account_data->id)) {
                    $no_errors = false;
                    $model->errors['phone'] = "Număr de telefon deja folosit";
                }
                if ($no_errors) {

                    $data = $model->get_data();
                    $salt = $data_mapper->get_salt_by_id($account_data->id);
                    $passwordSP = $data_mapper->get_password_by_id($account_data->id);
                    if ($services->password_check($model->data['old_password'], $salt, $passwordSP)) {
                        unset($data['confirm_password']);
                        unset($data['old_password']);
                        unset($data['created_at']);
                        if (!empty($data['password'])) {
                            $data['password'] = $services->generate_hash($services->add_salt_and_pepper($data['password'], $salt));
                        } else {
                            unset($data['password']);
                        }

                        if (!empty($_FILES['image'])) {

                            if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                                $blob = file_get_contents($_FILES['image']["tmp_name"]);
                                $data['image'] = pg_escape_bytea(DatabaseConnection::get_connection(), $blob);
                            }
                        }
                        $data_mapper->update_account_data($account_data->id, $data);
                    } else {
                        $model->errors['old_password'] = "Parolă greșită";
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
                Renderer::render_scripts("profile-page")
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
                Renderer::render_scripts("profile-page")
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
                die(json_encode($result));
            }
        }
        echo json_encode("false");
    }
}
