<?php
include_once DIR_CONTROLLERS . "Controller.php";
include_once DIR_VIEWS . "View.php";

class ItemImageController extends Controller
{
    public function __construct()
    {
    }

    public function get_image(Request $request)
    {
        $id = 1;

        $data_mapper = new AnnouncementDM();
        $row = $data_mapper->get_image($id);

        header('Content-Type: ' . $row['TYPE']);
        die(base64_decode($row['IMAGE']));
    }
}
