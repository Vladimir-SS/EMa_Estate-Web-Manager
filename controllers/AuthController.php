<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_CORE . "middlewares/LoggedMiddleware.php";
include_once DIR_MODELS . "LoginModel.php";
include_once DIR_MODELS . "RegisterModel.php";

class AuthController extends Controller
{
    public function __construct()
    {
        $this->register_middleware(new LoggedMiddleware(['login', 'register']));
    }

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
            $no_errors = $model->validate();

            if ($no_errors) {
                $data_mapper = new AccountDM();

                if ($data_mapper->check_existence_email($model->get_data()['email']['value']) > 0) {
                    $no_errors = false;
                    $model->errors['email'] = "Email deja înregistrat";
                }
                if ($data_mapper->check_existence_phone($model->get_data()['phone']['value']) > 0) {
                    $no_errors = false;
                    $model->errors['phone'] = "Număr de telefon deja înregistrat";
                }

                if ($no_errors) {

                    $data = $model->get_data();
                    unset($data["confirm-password"]);
                    $data["password_salt"]['type'] = 1;
                    $data["password_salt"]['value'] = $services->generate_salt();
                    $data["password"]['value'] = $services->generate_hash($services->add_salt_and_pepper($data["password"]['value'], $data["password_salt"]['value']));
                    $data_mapper->register_save($data);

                    header("Location: /login");
                    die();
                }
            }
            // echo Renderer::render_template("Page", [
            //     "title" => "Register",
            //     "content" => Renderer::render_template("register/register", ['model' => $model]),
            //     "styles" => Renderer::render_style("form")->add("icon")->add("login-register"),
            //     "scripts" => Renderer::render_script("form")
            // ]);
            // die();
            return $this->render(
                "Register",
                Renderer::render_template("register/register", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("login-register"),
                Renderer::render_script("form")
            );
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            // if (isset($_COOKIE['user']) && JWT::is_jwt_valid($_COOKIE['user']) == true) {
            //     header('Location: /home');
            //     die();
            // }
            // echo Renderer::render_template("Page", [
            //     "title" => "Register",
            //     "content" => Renderer::render_template("register/register", ['model' => $model]),
            //     "styles" => Renderer::render_style("form")->add("icon")->add("login-register"),
            //     "scripts" => Renderer::render_script("form")
            // ]);
            //die();
            return $this->render(
                "Register",
                Renderer::render_template("register/register", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("login-register"),
                Renderer::render_script("form")
            );
        }
    }

    public function login(Request $request)
    {
        $model = new LoginModel();
        if ($request->is_post()) {

            $services = new AccountService();
            $model->load($request->get_body());
            $no_errors = $model->validate();

            if ($no_errors) {
                $data_mapper = new AccountDM();

                $id = $data_mapper->find_id_by_email_or_phone($request->get_body()['email_or_phone']);

                if ($id != false) {
                    $salt = $data_mapper->get_salt_by_id($id);
                    $passwordSP = $data_mapper->get_password_by_id($id);
                    if ($services->password_check($model->data["password"], $salt, $passwordSP)) {
                        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
                        $payload = array('id' => $id, 'email_or_phone' => $request->get_body()['email_or_phone'], 'admin' => false, 'exp' => (time() + (86400 * 30)));

                        $jwt = JWT::generate_jwt($headers, $payload);

                        setcookie("user", $jwt, time() + (86400 * 30), "/"); // TODO: add httponly: true when you find a way to change pages form php

                        header("Location: /home");
                        die();
                    }
                }
            }
            // echo Renderer::render_template("Page", [
            //     "title" => "Login",
            //     "content" => Renderer::render_template("login/login", ['model' => $model]),
            //     "styles" => Renderer::render_style("form")->add("icon")->add("login-register"),
            //     "scripts" => ""
            // ]);
            // die();
            return $this->render(
                "Login",
                Renderer::render_template("login/login", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("login-register"),
                Renderer::render_script("form")
            );
        } else {
            if (isset($file_name)) {
                include DIR_CONTROLLERS . "RootFiles.php";
            }

            // if (isset($_COOKIE['user']) && JWT::is_jwt_valid($_COOKIE['user']) == true) {
            //     header('Location: /home');
            //     die();
            // }

            return $this->render(
                "Login",
                Renderer::render_template("login/login", ['model' => $model]),
                Renderer::render_style("form")->add("icon")->add("login-register")
            );

            // echo Renderer::render_template("Page", [
            //     "title" => "Login",
            //     "content" => Renderer::render_template("login/login", ['model' => $model]),
            //     "styles" => Renderer::render_style("form")->add("icon")->add("login-register"),
            //     "scripts" => ""
            // ]);
            // die();
        }
    }
}
