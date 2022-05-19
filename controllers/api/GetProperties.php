<?php

include_once DIR_MODELS . "AccountModel.php";
include_once DIR_BASE . "database/DatabaseConnection.php";

$response = [];

try {

    // $model = new AccountModel();

    // $model->load([
    //     "name" => "George Butco",
    //     "phone" => "0744704733",
    //     "email" => "george.butco@gmail.com",
    //     "business_name" => "buzniz"
    // ]);

    // $result = $model
    //     ->generateSalt()
    //     ->calculateHash("hey hey")
    //     ->validate();

    // if (sizeof($result) == 0) {
    //     //DatabaseConnection::getInstance()->save("accounts", $modele->getdata());

    //     echo $model->password_check("trouble") ? "1" : "0", "<br>";
    //     echo $model->password_check("mink") ? "1" : "0", "<br>";
    //     echo $model->password_check("hey hey") ? "1" : "0", "<br>";
    // }

    $data = DatabaseConnection::getInstance()->dataFindByID("accounts", 2);

    echo "<pre>";
    var_dump($data);
    echo "</pre>";

    // echo "<pre>";
    // var_dump($result);
    // echo "</pre>";
} catch (Exception $e) {
    $response = ["errors" => ["error" => $e->getMessage()]];
}

echo "<pre>";
var_dump($response);
echo "</pre>";
