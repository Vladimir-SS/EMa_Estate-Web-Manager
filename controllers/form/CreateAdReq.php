<?php

include_once DIR_MODELS . "AnnouncementModel.php";
include_once DIR_CONTROLLERS . "JWT.php";

echo "<pre>";
var_dump($_POST);
echo "</pre>";

$_POST['account_id'] = json_decode(get_jwt_payload($_COOKIE['user']))->id; // adds account_id value to the array before creating the AnnouncementModel

// Valori hardcoadate pana e gata filterul
$_POST['price'] = 9999;
$_POST['surface'] = 99;
$_POST['transaction_type'] = 'inchiriat';
//

$model = new AnnouncementModel();
$model->load($_POST);
$result = $model->validate();

echo "<pre>";
var_dump($result);
echo "</pre>";

if (empty($result)) {
    if (is_jwt_valid($_COOKIE['user']) == true) {
        $account_data = json_decode(get_jwt_payload($_COOKIE['user']));
        echo "<pre>";
        var_dump($account_data->id);
        echo "</pre>";
        $data_mapper = new AnnouncementDM();
        $data_mapper->createAnnouncement($model->getData());
        $id = $data_mapper->findIdByAccountIdAndTitle($account_data->id, $_POST['title']);

        foreach ($_FILES["images"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                if (!empty($_FILES["images"]["name"][$key])) {
                    $name = $_FILES["images"]["name"][$key];
                    $type = $_FILES["images"]["type"][$key];
                    $blob = addslashes(file_get_contents($_FILES["images"]["tmp_name"][$key]));
                    $data_mapper->addImage($id, $blob, $name, $type);
                }
            } else {
                echo 'Error during file upload ' . $error;
            }
        }
    }
} else {
    header("Location: /create-ad");
    die();
}

// echo "<pre>";
// var_dump($_FILES["images"]);
// echo "</pre>";

// foreach ($_FILES["images"]["error"] as $key => $error) {
//     if ($error == UPLOAD_ERR_OK) {
//         $tmp_name = $_FILES["images"]["tmp_name"][$key];
//         // basename() may prevent filesystem traversal attacks;
//         // further validation/sanitation of the filename may be appropriate
//         $name = basename($_FILES["images"]["name"][$key]);
//         echo "<pre>";
//         var_dump($_FILES["images"]["name"][$key]);
//         var_dump($name);
//         echo "</pre>";
//     }
// }
