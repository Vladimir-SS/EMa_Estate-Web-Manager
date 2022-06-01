<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_MODELS . "LoginModel.php";
include_once DIR_MODELS . "RegisterModel.php";

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $model = new RegisterModel();

        if ($request->is_post()) {


            $services = new AccountService();

            if (
                isset($request->get_body()["password"])
                && isset($request->get_body()["confirm-password"])
                && $request->get_body()["password"] !== $request->get_body()["confirm-password"]
            ) {
                $model->errors['confirm-password'] = "Parolele nu coincid!";
            }

            $model->load($request->get_body());
            $errors = $model->validate();

            if ($errors) {
                $data_mapper = new AccountDM();

                $data = $model->get_data();
                unset($data["confirm-password"]);
                $data["password_salt"]['type'] = 1;
                $data["password_salt"]['value'] = $services->generate_salt();
                $data["password"]['value'] = $services->generate_hash($services->add_salt_and_pepper($data["password"]['value'], $data["password_salt"]['value']));
                $data_mapper->register_save($data);
                header("Location: /login");
                die();
            } else {
                echo View::render_template("Page", [
                    "title" => "Register",
                    "content" => View::render_template("register/register", ['model' => $model]),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => View::render_script("form")
                ]);
            }
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            if (isset($_COOKIE['user'])) {
                if (JWT::is_jwt_valid($_COOKIE['user']) == true) {
                    header('Location: /home');
                    die();
                }
            } else {
                echo View::render_template("Page", [
                    "title" => "Register",
                    "content" => View::render_template("register/register", ['model' => $model]),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => View::render_script("form")
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $model = new LoginModel();
        if ($request->is_post()) {

            $services = new AccountService();
            $model->load($request->get_body());
            $result = $model->validate();
            $data_mapper = new AccountDM();

            $id = $data_mapper->find_id_by_email_or_phone($request->get_body()['email_or_phone']);

            if ($id != false) {
                $salt = $data_mapper->getIdSalt($id);
                $passwordSP = $data_mapper->getPasswordById($id);
                if ($services->password_check($model->data["password"], $salt, $passwordSP)) {
                    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
                    $payload = array('id' => $id, 'email_or_phone' => $request->get_body()['email_or_phone'], 'admin' => false, 'exp' => (time() + (86400 * 30)));

                    $jwt = JWT::generate_jwt($headers, $payload);

                    setcookie("user", $jwt, time() + (86400 * 30), "/"); // TODO: add httponly: true when you find a way to change pages form php

                    header("Location: /home");
                    die();
                } else {
                    echo View::render_template("Page", [
                        "title" => "Login",
                        "content" => View::render_template("login/login", ['model' => $model]),
                        "styles" => View::render_style("form")->add("icon")->add("login-register"),
                        "scripts" => ""
                    ]);
                }
            } else {
                echo View::render_template("Page", [
                    "title" => "Login",
                    "content" => View::render_template("login/login", ['model' => $model]),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => ""
                ]);
            }
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            if (isset($_COOKIE['user'])) {
                if (JWT::is_jwt_valid($_COOKIE['user']) == true) {
                    header('Location: /home');
                    die();
                }
            } else {
                echo View::render_template("Page", [
                    "title" => "Login",
                    "content" => View::render_template("login/login", ['model' => $model]),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => ""
                ]);
            }
        }
    }
}
