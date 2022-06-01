<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_MODELS . "LoginModel.php";
include_once DIR_MODELS . "RegisterModel.php";

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->is_post()) {

            $model = new RegisterModel();
            $services = new AccountService();

            if (
                isset($request->get_body()["password"])
                && isset($request->get_body()["confirm-password"])
                && $request->get_body()["password"] !== $request->get_body()["confirm-password"]
            ) {
                //die(json_encode(["errors" => "Parolele nu coincid!"]));
                $model->errors['confirm-password'] = "Parolele nu coincid!";
            }

            $model->load($request->get_body());
            $errors = $model->validate();

            // echo "<pre>";
            // var_dump($model->errors);
            // echo "</pre>";
            // echo $errors['last_name'];

            $model->data["password_salt"] = $services->generate_salt();
            $model->data["password"] = $services->generate_hash($services->add_salt_and_pepper($request->get_body()["password"], $model->data["password_salt"]));

            if ($errors) {
                $data_mapper = new AccountDM();
                $data_mapper->register_save($model->get_data());
                header("Location: /login");
                die();
            } else {
                header("Location: /register");
                die();
            }
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            if (isset($_COOKIE['user'])) {
                if (is_jwt_valid($_COOKIE['user']) == true) {
                    header('Location: /home');
                    die();
                }
            } else {
                echo View::render_template("Page", [
                    "title" => "Register",
                    "content" => View::render_content("register/register"),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => View::render_script("form")
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        if ($request->is_post()) {

            $model = new LoginModel();
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

                    $jwt = generate_jwt($headers, $payload);

                    setcookie("user", $jwt, time() + (86400 * 30), "/"); // TODO: add httponly: true when you find a way to change pages form php

                    header("Location: /home");
                    die();
                } else {
                    header("Location: /login");
                    die();
                }
            } else {
                header("Location: /login");
                die();
            }
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            if (isset($_COOKIE['user'])) {
                if (is_jwt_valid($_COOKIE['user']) == true) {
                    header('Location: /home');
                    die();
                }
            } else {
                echo View::render_template("Page", [
                    "title" => "Login",
                    "content" => View::render_content("login/login"),
                    "styles" => View::render_style("form")->add("icon")->add("login-register"),
                    "scripts" => ""
                ]);
            }
        }
    }
}
