<?php
include_once DIR_MODELS . "Announcement/AnnouncementDM.php";

class ItemsController extends Controller
{
    private $count = 10;
    private $index = 0;

    public function get_items(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data_mapper = new AnnouncementDM();
        if (isset($request->get_body()['count']) && is_numeric($request->get_body()['count'])) {
            $this->count = $request->get_body()['count'];
        }
        if (isset($request->get_body()['index']) && is_numeric($request->get_body()['index'])) {
            $this->index = $request->get_body()['index'];
        }
        $data = $data_mapper->get_announcements($this->count, $this->index);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_item(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data_mapper = new AnnouncementDM();
        if (isset($request->get_body()['id']) && is_numeric($request->get_body()['id'])) {
            $data = $data_mapper->get_announcement_by_id($request->get_body()['id']);
            if ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
                echo json_encode(["err" => "No announcement with this id"]);
            }
            die();
        }
        echo json_encode(["err" => "No id found in request body"]);
    }

    public function get_close_located_items(Request $request)
    {
        $data_mapper = new AnnouncementDM();
        if ((!empty($request->get_body()['lat'])
                && !empty($request->get_body()['lon']))
            &&  is_numeric($request->get_body()['lat'])
            && is_numeric($request->get_body()['lon'])
        ) {
            $latMin = $request->get_body()['lat'] - 0.003;
            $latMax = $request->get_body()['lat'] + 0.003;
            $lonMin = $request->get_body()['lon'] - 0.005;
            $lonMax = $request->get_body()['lon'] + 0.005;

            $data = $data_mapper->get_close_located_items($latMin, $latMax, $lonMin, $lonMax);
            if ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
                echo json_encode([]);
            }
            die();
        }
        echo json_encode(["err" => "Incorrect parameters"]);
    }

    public function get_filtered_items(Request $request)
    {
        $filter = $request->get_body();
        $data_mapper = new AnnouncementDM();
        $data = $data_mapper->get_filtered_announcements($filter, 20);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
