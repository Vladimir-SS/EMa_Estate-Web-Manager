<?php

include_once DIR_MODELS . "LoginModel.php";

echo "<pre>";
var_dump($_POST);
echo "<\pre>";

$model = new LoginModel();
$services = new AccountService();
$model->load($_POST);
$result = $model->validate();

echo "<pre>";
var_dump($result);
echo "<\pre>";


$data_mapper = new AccountDM();

$id = $data_mapper->findIdByEmailOrPhone($_POST['email_or_phone']);

if ($id != false) {
    $salt = $data_mapper->getIdSalt($id);
    $passwordSP = $data_mapper->getPasswordById($id);
    if ($services->password_check($model->data["password"], $salt, $passwordSP)) {
        setcookie("user", "loggedIn", time() + (86400 * 30), "/");
        header("Location: /home");
        die();
    }
}
header("Location: /login");
die();
// save user id | random cookie | timer in a table to check if user is loggedin 
