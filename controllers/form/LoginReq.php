<?php

include_once DIR_MODELS . "LoginModel.php";
include_once DIR_CONTROLLERS . "JWT.php";

$model = new LoginModel();
$services = new AccountService();
$model->load($_POST);
$result = $model->validate();
$data_mapper = new AccountDM();

$id = $data_mapper->findIdByEmailOrPhone($_POST['email_or_phone']);

if ($id != false) {
    $salt = $data_mapper->getIdSalt($id);
    $passwordSP = $data_mapper->getPasswordById($id);
    if ($services->password_check($model->data["password"], $salt, $passwordSP)) {
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('sub' => '1234567890', 'email_or_phone' => $_POST['email_or_phone'], 'admin' => false, 'exp' => (time() + (86400 * 30)));

        $jwt = generate_jwt($headers, $payload);

        setcookie("user", $jwt, time() + (86400 * 30), "/"); // TODO: add httponly: true when you find a way to change pages form php

        header("Location: /home");
        die();
    } else {
        header("Location: /login");
        die();
    }
}
