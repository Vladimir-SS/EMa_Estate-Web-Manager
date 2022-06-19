<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CORE . "exceptions/NotFoundException.php";

class ImageController extends Controller
{
    public function get_image(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data_mapper = new AnnouncementDM();
        $data = $request->get_body();

        if (isset($data['announcement_id']) && is_numeric($data['announcement_id'])) {
            $row = $data_mapper->get_image($data['announcement_id']);

            if ($row) {
                header('Content-Type: ' . $row['TYPE']);
                die(base64_decode($row['IMAGE']));
            } else {
                echo json_encode([]);
            }
            die();
        } elseif (isset($data['id']) && is_numeric($data['id'])) {
            $row = $data_mapper->get_image_by_id($data['id']);

            if ($row) {
                header('Content-Type: ' . $row['TYPE']);
                die(base64_decode($row['IMAGE']));
            } else {
                echo json_encode([]);
            }
            die();
        }
        echo json_encode(["err" => "No announcement_id or id found in request body"]);
    }

    public function get_profile_image(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data = $request->get_body();

        if (isset($data['id']) && is_numeric($data['id'])) {
            $data_mapper = new AccountDM();
            $row = $data_mapper->get_image($data['id']);

            if ($row) {
                header('Content-Type: ' . $row['IMAGE_TYPE']);
                die(base64_decode($row['IMAGE']));
            } else {
                echo json_encode([]);
            }
            die();
        }
        echo json_encode(["err" => "No id found in request body"]);
    }
}
