<?php

include_once DIR_MODELS . "RegisterModel.php";

echo "<pre>";
var_dump($_POST);
echo "<\pre>";

if (isset($_POST["password"]) && isset($_POST["confirm-password"]) && $_POST["password"] !== $_POST["confirm-password"]) {
    die(json_encode(["errors" => "Parolele nu coincid!"]));
}
$model = new RegisterModel();
$services = new AccountService();
$model->load($_POST);
$result = $model->validate();

$model->data["password_salt"] = $services->generateSalt();
$model->data["password"] = $services->generateHash($services->addSaltAndPepper($_POST["password"], $model->data["password_salt"]));

echo "<pre>";
var_dump($result);
echo "<\pre>";

if (empty($result)) {
    $data_mapper = new AccountDM();
    $data_mapper->registerSave($model->getData());
    header("Location: /login");
    die();
    // save user id | random cookie | timer in a table to check if user is loggedin 
} else {
    header("Location: /register");
    die();
}
