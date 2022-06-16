<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";
include_once DIR_CORE . "exceptions/NotFoundException.php";

class ImageController extends Controller
{
    public function __construct()
    {
    }

    public function get_image(Request $request)
    {
        $data = $request->get_body();

        $data_mapper = new AnnouncementDM();
        $row = $data_mapper->get_image($data['id']);

        if ($row) {
            header('Content-Type: ' . $row['TYPE']);
            die(base64_decode($row['IMAGE']));
        } else {
            echo "No data found";
        }
    }
}
