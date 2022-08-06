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

            return $this->render(
                "Register",
                Renderer::render_template("register/register", ['model' => $model]),
                Renderer::render_styles("form", "icon", "login-register"),
                Renderer::render_scripts("form")
            );
        } else {
            return $this->render(
                "Register",
                Renderer::render_template("register/register", ['model' => $model]),
                Renderer::render_styles("form", "icon", "login-register"),
                Renderer::render_scripts("form")
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

                $id = $data_mapper->find_id_by_email_or_phone($request->get_body()['EMAIL_OR_PHONE']);

                if ($id != false) {
                    $salt = $data_mapper->get_salt_by_id($id);
                    $passwordSP = $data_mapper->get_password_by_id($id);
                    if ($services->password_check($model->data['PASSWORD'], $salt, $passwordSP)) {
                        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
                        $payload = array('id' => $id, 'email_or_phone' => $request->get_body()['EMAIL_OR_PHONE'], 'admin' => false, 'exp' => (time() + (86400 * 30)));

                        $jwt = JWT::generate_jwt($headers, $payload);

                        setcookie("user", $jwt, time() + (86400 * 30), "/");

                        header("Location: /home");
                        die();
                    } else {
                        $model->errors['PASSWORD'] = "Parola sau emailul/numărul de telefon greșit";
                    }
                } else {
                    $model->errors['EMAIL_OR_PHONE'] = "Nu există cont cu emailul/numărul de telefon introdus";
                }
            }
            return $this->render(
                "Login",
                Renderer::render_template("login/login", ['model' => $model]),
                Renderer::render_styles("form", "icon", "login-register"),
                Renderer::render_scripts("form")
            );
        } else {
            return $this->render(
                "Login",
                Renderer::render_template("login/login", ['model' => $model]),
                Renderer::render_styles("form", "icon", "login-register")
            );
        }
    }
}
