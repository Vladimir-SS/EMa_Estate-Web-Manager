<?php
include_once DIR_MODELS . "Announcement/AnnouncementDM.php";

class ItemsController extends Controller
{
    private $count = 10;
    private $index = 0;

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
        $data['COUNT'] = $data_mapper->get_announcements_count();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_filtered_items(Request $request)
    {
    }
}
