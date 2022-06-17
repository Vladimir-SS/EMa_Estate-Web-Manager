<?php
include_once DIR_MODELS . "Announcement/AnnouncementDM.php";

class ItemsController extends Controller
{
    private $count = 10;
    private $index = 0;

    private $types = ["apartment", "house", "office", "land"];
    private $ap_types = [
        "Nespecificat", "Decomandat", "Nedecomandat", "Semidecomandat", "Circular", "Open-space"
    ];

    public function get_items(Request $request)
    {
        $data_mapper = new AnnouncementDM();
        if (isset($request->get_body()['count'])) {
            $this->count = $request->get_body()['count'];
        }
        if (isset($request->get_body()['index'])) {
            $this->index = $request->get_body()['index'];
        }
        $data = $data_mapper->get_announcements($this->count, $this->index);
        //$data['COUNT'] = $data_mapper->get_announcements_count();]
        //echo '<pre>';
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_item(Request $request)
    {
        $data_mapper = new AnnouncementDM();
        if (isset($request->get_body()['id'])) {
            $data = $data_mapper->get_announcement_by_id($request->get_body()['id']);
            if ($data) {
                //echo '<pre>';
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
                echo "No data found";
            }
            die();
        }
        echo "No id found in request body";
    }

    public function get_filtered_items(Request $request)
    {
        $filter = $request->get_body();
        $data_mapper = new AnnouncementDM();
        $data = $data_mapper->get_filtered_announcements($filter, 20);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
