<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";
include_once DIR_SHARED . "BidirectionalMap.php";

//TODO: add this under AnnouncementDM namespace and transform the class AnnouncementDM into namespace
// OR make a data structure that contains typed constraints and api key transition and queries
//TODO: add "by" here


class AnnouncementDM
{
    static private BidirectionalMap $apiKeyMap;

    static public function init(){
        AnnouncementDM::$apiKeyMap = (new BidirectionalMap())
        ->setAll([
            ['type'],
            ['basement'],
            ['built_in', 'builtIn'],
            ['ap_type', 'apartmentType'],
            ['parking_lots', 'parkingLots'],
            ['rooms'],
            ['floor'],
            ['transaction_type', 'transaction'],
            ['announcement_id', 'announcementId'],
            ['bathrooms'], ['title'], ['description'], ['lat'], ['lon'], ['address'], ['surface'], ['price'],
            ['type', 'by'] //TODO: needs to be ignored by now
        ]);
    }

    static public function clearData(&$data){
        foreach ($data as $key => $value)
            if($value == null)
                unset($data[$key]);
    }

    static public function API2SQL($data){
        $rv = [];
        foreach ($data as $key => $value) {
            if(!AnnouncementDM::$apiKeyMap->backwardExists($key))
                throw new InternalException("field '$key' can't be handled");

            $convertedKey = AnnouncementDM::$apiKeyMap->backwards($key);
            $rv[$convertedKey] = $data[$key];
        }
        return $rv;
    }

    static public function SQL2API($data){
        $rv = [];
        foreach ($data as $key => $value) {
            if(!AnnouncementDM::$apiKeyMap->forwardExists($key))
                throw new InternalException("field '$key' can't be handled");

            $convertedKey = AnnouncementDM::$apiKeyMap->forward($key);
            $rv[$convertedKey] = $data[$key];
        }
        return $rv;
    }

    /**
     * Checks if the title already exists in the database
     * 
     * @param $filter  - filter URLparams array
     * @param $count   - maximum number of announcements to be returned
     * @return array
     */
    static public function get_filtered_announcements($filter, $count)
    {
        AnnouncementDM::init();
        if ($filter['type'] === "house")
            AnnouncementDM::$apiKeyMap->set('floor', 'floors');
        $filterQuery = AnnouncementDM::get_announcements_filters_string($filter);


        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT * from announcements LEFT JOIN buildings b on announcements.id = b.announcement_id WHERE " . $filterQuery['where'] . "LIMIT $count";
        $result = pg_query_params($dbconn,  $sql, $filterQuery['values']) or throw new InternalException("Database Problem");
    
        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $rv = [];

        while ($row = pg_fetch_assoc($result)) {

            $itemId = $row['id'];

            foreach ($row as $key => $value) {
                if($value === null || !AnnouncementDM::$apiKeyMap->forwardExists($key))
                    unset($row[$key]);
                else{
                    unset($row[$key]);
                    $row[AnnouncementDM::$apiKeyMap->forward($key)] = $value;
                }
            }

            $row['imageURL'] = "api/items/image?announcement_id=" . $itemId;
            $row['id'] = $itemId;
            array_push($rv, $row);
        }

        return $rv;
    }




    /**
     * Creates a string for the filter array ( <column_name> <comparator> <filter_value> AND ...)
     * 
     * @param $filter  - filter URLparams array
     * @return string
     */
    static public function get_announcements_filters_string($filter): array
    {
        $conjuctionData = [
            'where' => [],
            'values' => []
        ];
        $i = 1;

        foreach ($filter as $key => $value) {
            if($value === "*")
                continue;
            $constrain = null;
            if(str_ends_with($key, 'Min')){
                $backwardKey = substr_replace($key ,"", -3);
                if(AnnouncementDM::$apiKeyMap->backwardExists($backwardKey))
                    $constrain = AnnouncementDM::$apiKeyMap->backward($backwardKey) . " >= $" . $i++;
                else throw new InternalException("Unrecognized key '$key'");
            }
            elseif(str_ends_with($key, 'Max')){
                $backwardKey = substr_replace($key ,"", -3);
                if(AnnouncementDM::$apiKeyMap->backwardExists($backwardKey))
                    $constrain = AnnouncementDM::$apiKeyMap->backward($backwardKey) . " <= $" . $i++;
                else throw new InternalException("Unrecognized key '$key'");
            }
            else {
                if(AnnouncementDM::$apiKeyMap->backwardExists($key))
                    $constrain = AnnouncementDM::$apiKeyMap->backward($key) . " = $" . $i++;
                else throw new InternalException("Unrecognized key '$key'");
            }

            if(empty($constrain)) continue;

            array_push($conjuctionData['values'], $value);
            array_push($conjuctionData['where'], $constrain);
        }

        $conjuctionData['where'] = implode(' AND ', $conjuctionData['where']);

        return $conjuctionData;
    }


    //TODO: automatic var name change
    /**
     * Get an announcement by id
     * 
     * @param $id
     * @return array
     */
    static public function get_announcement_by_id($id)
    {
        
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT id,account_id,title,price,surface,address,lon,lat,transaction_type,description,type FROM announcements WHERE id = $1";
        $result = pg_query_params($dbconn,  $sql, array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);

        if(!$row)
            return false;

        if ($row['type'] !== "land") {
            $row = array_merge($row, AnnouncementDM::get_building($row['id'], $row['type']));
        }
        $row['transactionType'] = $row['transaction_type'];
        unset($row['transaction_type']);
        $row['accountID'] = $row['account_id'];
        unset($row['account_id']);

        $row['imagesURLs'] = AnnouncementDM::get_announcement_images_urls($row['id']);

        return $row;
    }
    /**
     * Get all announcements posted by an user
     * 
     * @param $account_id
     * @return array
     */
    static public function get_announcements_of_id($account_id)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM announcements WHERE account_id = $1 LIMIT 10"; //pagination or auto on scroll

        $result = pg_query_params($dbconn,  $sql, array($account_id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $data = [];

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_LOWER);
            if ($row['type'] !== "land") {
                $row = array_merge($row, AnnouncementDM::get_building($row['id'], $row['type']));
            }
            $row['transactionType'] = $row['transaction_type'];
            unset($row['transaction_type']);

            $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
            array_push($data, $row);
        }

        return $data;
    }

    /**
     * Returns announcements
     * 
     * @param $count   - maximum number of announcements to be returned
     * @param $index   - the starting index
     * @return array
     */
    static public function get_announcements($count, $index = 0)
    {
       
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT id,title,price,surface,address,lon,lat,transaction_type,description,type FROM announcements LIMIT $1 OFFSET $2";
        $result = pg_query_params($dbconn,  $sql, array($count, $index));

        if(!$result)
            throw new InternalException("Database Problem");

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $data = [];

        for ($i = 0; $i <= $count; $i++) {
            if ($row = pg_fetch_assoc($result)) {
                if ($row['type'] !== "land") {
                    $row = array_merge($row, AnnouncementDM::get_building($row['id'], $row['type']));
                }
                $row['transactionType'] = $row['transaction_type'];
                unset($row['transaction_type']);

                $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
                $data[$i] = $row;
            } else {
                break;
            }
        }

        return $data;
    }

    //TODO: better way of doing it
    static public function get_building($id, $type)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM buildings WHERE announcement_id = $1";
        // if ($type === "house") {
        //     $sql = "SELECT floor,bathrooms,basement,built_in,parking_lots,rooms FROM buildings WHERE announcement_id = $id";
        // } elseif ($type === "office") {
        //     $sql = "SELECT bathrooms,parking_lots,built_in FROM buildings WHERE announcement_id = $id";
        // } elseif ($type === "apartment") {
        //     $sql = "SELECT ap_type,floor,bathrooms,parking_lots,built_in,rooms FROM buildings WHERE announcement_id = $id";
        // }

        $result = pg_query_params($dbconn,  $sql, array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);
    
        // if ($type === "house" && $row['floor']) {
        //     $row['floors'] = $row['floor'];
        //     unset($row['floor']);
        // }
        //elseif ($type === "apartment") {
        //     $row['apartmentType'] = $row['ap_type'];
        //     unset($row['ap_type']);
        // }
        // $row['builtIn'] = $row['built_in'];
        // unset($row['built_in']);
        // $row['parkingLots'] = $row['parking_lots'];
        // unset($row['parking_lots']);

        $rv = AnnouncementDM::SQL2API($row);
        AnnouncementDM::clearData($rv);

        if ($type === "house" && $rv['floor']) {
            $row['floors'] = $row['floor'];
            unset($row['floor']);
        }

        return $rv ;
    }

    static public function get_image($announcement_id)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT name,type,image FROM images WHERE announcement_id = $1";
        $result = pg_query_params($dbconn,  $sql, array($announcement_id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);
        $row['image'] = pg_unescape_bytea($row['image']);
        return $row;
    }

    static public function get_image_by_id($id)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT name,type,image FROM images WHERE id = $1";
        $result = pg_query_params($dbconn,  $sql, array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);
        $row['image'] = pg_unescape_bytea($row['image']);
        return $row;
    }

    static public function get_announcement_images_urls($announcement_id)
    {

        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM images WHERE announcement_id = $1", array($announcement_id));
        
        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $imagesURLs = [];
        $index = 0;

        while ($row = pg_fetch_assoc($result)) {
            $imagesURLs[$index] = "api/items/image?id=" . $row['id'];
            $index++;
        }

        return $imagesURLs;
    }




    //TODO: name and type are useless, remove them
    static public function add_image($announcement_id, $blob, $name, $type)
    {
        $data = [
            'announcement_id' => $announcement_id,
            'name' => $name,
            'type' => $type,
            'image' => pg_escape_bytea(DatabaseConnection::get_connection(), $blob)
        ];


        Model::save_data('images', $data);
    }


    //TODO: delete this "no id with same title thing"

    /**
     * Checks if the title already exists in the database
     * 
     * @param $title
     * @param $id
     * @return bool true if exists, false otherwise
     */
    static public function check_existence_title($title, $id): bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM announcements WHERE title = $1 AND account_id = $2", array($title, $id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);

        return !!$row;
    }

    static public function create_announcement(array $data)
    {
        $result = Model::save_data('announcements', $data, ['id']);

        return $result['id'];
    }

    static public function get_close_located_items($latMin, $latMax, $lonMin, $lonMax)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT id, lat, lon, price, type FROM announcements WHERE lat <= $1 AND lat >= $2 AND lon <= $3 AND lon >= $4";
        $result = pg_query_params($dbconn,  $sql, array($latMax, $latMin, $lonMax, $lonMin));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);

        $data = [];

        while ($row = pg_fetch_assoc($result)) {
            $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
            array_push($data, $row);
        }
        return $data;
    }

    static public function delete_announcement_of_id($account_id, $announcement_id)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM announcements WHERE account_id = $1 AND id = $2";

        $result = pg_query_params($dbconn, $sql, [$account_id, $announcement_id]);

        return $result;
    }
}

AnnouncementDM::init();