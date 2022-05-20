<?php

include_once DIR_MODELS . "AccountModel.php";

echo "<pre>";
var_dump($_POST);
echo "<\pre>";

if (isset($_POST["password"]) && isset($_POST["confirm-password"]) && $_POST["password"] !== $_POST["confirm-password"]) {
    die(json_encode(["errors" => "Parolele nu coincid!"]));
}
$model = new AccountModel();
$model->load($_POST);
$model->generateSalt()->generateHash($_POST["password"]);
$result = $model->validate();

echo "<pre>";
var_dump($result);
echo "<\pre>";

if (empty($result)) {
    $conn = DatabaseConnection::getInstance(); // Should be moved
    $conn->dataSave("accounts", $model->getData());
    setcookie("user", "loggedIn", time() + (86400 * 30), "/");

    $conn->close();
} else {
}
