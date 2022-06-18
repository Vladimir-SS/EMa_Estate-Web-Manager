<?php
include_once DIR_MODELS . "account/AccountDM.php";

class ProfileDataController extends Controller
{

    public function get_profile_data(Request $request)
    {
        $data_mapper = new AccountDM();
        if (isset($request->get_body()['id'])) {
            $data = $data_mapper->get_data_by_id($request->get_body()['id']);
            if ($data) {
                $data = array_change_key_case($data, CASE_LOWER);
                $data['lastName'] = $data['last_name'];
                $data['firstName'] = $data['first_name'];
                $data['businessName'] = $data['business_name'];
                $data['createdAt'] = $data['created_at'];
                unset($data['last_name']);
                unset($data['first_name']);
                unset($data['business_name']);
                unset($data['created_at']);

                $data['imageURL'] = "api/profile/image?id=" . $request->get_body()['id'];
                //echo '<pre>';
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
                echo "No data found";
            }
            die();
        }
        echo "No id found in request body";
    }

    public function get_profile_items(Request $request)
    {
        $data_mapper = new AnnouncementDM();
        if (isset($request->get_body()['id'])) {
            $data = $data_mapper->get_announcements_of_id($request->get_body()['id']);
            echo json_encode($data, JSON_PRETTY_PRINT);
            die();
        } else {
            if (!Application::isGuest()) {
                $account_data = json_decode(JWT::get_jwt_payload($_COOKIE['user']));
                $data = $data_mapper->get_announcements_of_id($account_data->id);
                echo json_encode($data, JSON_PRETTY_PRINT);
            }
            die();
        }
        echo "No id found in request";
    }
}
