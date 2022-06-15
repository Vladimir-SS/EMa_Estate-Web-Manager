<?php

include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CORE . "middlewares/AuthMiddleware.php";
include_once DIR_MODELS . "ProfileModel.php";

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->register_middleware(new AuthMiddleware(['profile']));
    }

    public function profile(Request $request)
    {
        $model = new ProfileModel();
        $image = "";
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

                if ($data_mapper->check_existence_email($model->get_data()['EMAIL']['value']) > 0) {
                    $no_errors = false;
                    $model->errors['EMAIL'] = "Email deja folosit";
                }
                if ($data_mapper->check_existence_phone($model->get_data()['PHONE']['value']) > 0) {
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
                        $data['PASSWORD']['value'] = $services->generate_hash($services->add_salt_and_pepper($data['PASSWORD']['value'], $salt));
                        $data_mapper->update_account_data($account_data->id, $data);
                    }
                }
            }
            if (($data = $data_mapper->get_data_by_id($account_data->id)) != false) {
                $model->load($data);
            } else {
                throw new Exception("Couldn't find account");
            }
            $model->load($data);
            return $this->render(
                "Profil",
                Renderer::render_template("profile/profile", ['model' => $model, 'image' => $image]),
                Renderer::render_style("form")->add("icon")->add("item")->add("search")->add("profile"),
                Renderer::render_script("avatar-loader")
            );
        } else {
            $data_mapper = new AccountDM();
            $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
            if (($data = $data_mapper->get_data_by_id($account_data->id)) != false) {
                $model->load($data);
            } else {
                throw new Exception("Couldn't find account");
            }
            return $this->render(
                "Profil",
                Renderer::render_template("profile/profile", ['model' => $model, 'image' => $image]),
                Renderer::render_style("form")->add("icon")->add("item")->add("search")->add("profile"),
                Renderer::render_script("avatar-loader")->add("Item")
            );
        }
    }
}
