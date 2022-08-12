<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

class AnnouncementDM
{
    /**
     * Checks if the title already exists in the database
     * 
     * @param $filter  - filter URLparams array
     * @param $count   - maximum number of announcements to be returned
     * @return array
     */
    public function get_filtered_announcements($filter, $count)
    {
        DatabaseConnection::get_connection();

        $sql = "SELECT a.id,a.title,a.price,a.surface,a.address,a.lon,a.lat,a.transaction_type,a.description,a.type, b.floor,b.bathrooms,b.basement,b.built_in,b.parking_lots,b.ap_type,b.rooms FROM announcements a LEFT JOIN buildings b ON a.id = b.announcement_id WHERE" . $this->get_announcements_filters_string($filter);

        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $data = [];

        for ($i = 0; $i <= $count; $i++) {
            if (($row = oci_fetch_assoc($stmt)) != false) {
                $row = array_change_key_case($row, CASE_LOWER);
                if ($row['type'] === "land") {
                    unset($row['ap_type']);
                    unset($row['basement']);
                    unset($row['parking_lots']);
                    unset($row['bathrooms']);
                    unset($row['rooms']);
                    unset($row['floor']);
                    unset($row['built_in']);
                } else {
                    $row['builtIn'] = $row['built_in'];
                    unset($row['built_in']);
                    $row['parkingLots'] = $row['parking_lots'];
                    unset($row['parking_lots']);

                    if ($row['type'] === "house") {
                        $row['floors'] = $row['floor'];
                        unset($row['floor']);
                        unset($row['ap_type']);
                    } elseif ($row['type'] === "apartment") {
                        $row['apartmentType'] = $row['ap_type'];
                        unset($row['ap_type']);
                        unset($row['basement']);
                    } elseif ($row['type'] === "office") {
                        unset($row['rooms']);
                        unset($row['basement']);
                        unset($row['ap_type']);
                        unset($row['floor']);
                    }
                }

                $row['transactionType'] = $row['transaction_type'];
                unset($row['transaction_type']);

                $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
                $data[$i] = $row;
            } else {
                break;
            }
        }

        oci_free_statement($stmt);
        DatabaseConnection::close();

        return $data;
    }

    /**
     * Creates a string for the filter array ( <column_name> <comparator> <filter_value> AND ...)
     * 
     * @param $filter  - filter URLparams array
     * @return string
     */
    public function get_announcements_filters_string($filter): string
    {
        $filters = [];
        if (isset($filter['type'])) {
            $temp = $filter['type'];
            array_push($filters, " a.type = '$temp'");
        }

        if (isset($filter['priceMin'])) {
            $temp = $filter['priceMin'];
            array_push($filters, " a.price >= $temp");
        }

        if (isset($filter['priceMax'])) {
            $temp = $filter['priceMax'];
            array_push($filters, " a.price <= $temp");
        }

        if (isset($filter['priceMax'])) {
            $temp = $filter['priceMax'];
            array_push($filters, " a.price <= $temp");
        }

        if (isset($filter['transaction'])) {
            $temp = $filter['transaction'];
            array_push($filters, " a.transaction_type = '$temp'");
        }

        if (isset($filter['roomsMin'])) {
            $temp = $filter['roomsMin'];
            array_push($filters, " b.rooms >= $temp");
        }

        if (isset($filter['roomsMax'])) {
            $temp = $filter['roomsMax'];
            array_push($filters, " b.rooms <= $temp");
        }

        if (isset($filter['bathroomsMin'])) {
            $temp = $filter['bathroomsMin'];
            array_push($filters, " b.bathrooms >= $temp");
        }

        if (isset($filter['bathroomsMax'])) {
            $temp = $filter['bathroomsMax'];
            array_push($filters, " b.bathrooms <= $temp");
        }

        if (isset($filter['builtInMin'])) {
            $temp = $filter['builtInMin'];
            array_push($filters, " b.built_in >= $temp");
        }

        if (isset($filter['builtInMax'])) {
            $temp = $filter['builtInMax'];
            array_push($filters, " b.built_in <= $temp");
        }

        if (isset($filter['apType'])) {
            $temp = $filter['apType'];
            array_push($filters, " b.ap_type = '$temp'");
        }

        $filters = implode(" AND ", $filters);
        return $filters;
    }

    /**
     * Get an announcement by id
     * 
     * @param $id
     * @return array
     */
    public function get_announcement_by_id($id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id,account_id,title,price,surface,address,lon,lat,transaction_type,description,type FROM announcements WHERE id= $id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        $row = oci_fetch_assoc($stid);

        $row = array_change_key_case($row, CASE_LOWER);
        if ($row['type'] !== "land") {
            $row = array_merge($row, $this->get_building($row['id'], $row['type']));
        }
        $row['transactionType'] = $row['transaction_type'];
        unset($row['transaction_type']);
        $row['accountID'] = $row['account_id'];
        unset($row['account_id']);

        $row['imagesURLs'] = $this->get_announcement_images_urls($row['id']);

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }
    /**
     * Get all announcements posted by an user
     * 
     * @param $account_id
     * @return array
     */
    public function get_announcements_of_id($account_id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id,title,price,surface,address,lon,lat,transaction_type,description,type FROM announcements WHERE account_id = $account_id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $data = [];

        while (($row = oci_fetch_assoc($stid)) != false) {
            $row = array_change_key_case($row, CASE_LOWER);
            if ($row['type'] !== "land") {
                $row = array_merge($row, $this->get_building($row['id'], $row['type']));
            }
            $row['transactionType'] = $row['transaction_type'];
            unset($row['transaction_type']);

            $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
            array_push($data, $row);
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $data;
    }

    /**
     * Returns announcements
     * 
     * @param $count   - maximum number of announcements to be returned
     * @param $index   - the starting index
     * @return array
     */
    public function get_announcements($count, $index = 0)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id,title,price,surface,address,lon,lat,transaction_type,description,type FROM (SELECT rownum AS rn, a.* FROM announcements a) WHERE rn > $index AND rn <= $index+$count";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $data = [];

        for ($i = 0; $i <= $count; $i++) {
            if (($row = oci_fetch_assoc($stid)) != false) {
                $row = array_change_key_case($row, CASE_LOWER);
                if ($row['type'] !== "land") {
                    $row = array_merge($row, $this->get_building($row['id'], $row['type']));
                }
                $row['transactionType'] = $row['transaction_type'];
                unset($row['transaction_type']);

                $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
                $data[$i] = $row;
            } else {
                break;
            }
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $data;
    }

    public function get_building($id, $type)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT * FROM buildings WHERE announcement_id = $id";;
        if ($type === "house") {
            $sql = "SELECT floor,bathrooms,basement,built_in,parking_lots,rooms FROM buildings WHERE announcement_id = $id";
        } elseif ($type === "office") {
            $sql = "SELECT bathrooms,parking_lots,built_in FROM buildings WHERE announcement_id = $id";
        } elseif ($type === "apartment") {
            $sql = "SELECT ap_type,floor,bathrooms,parking_lots,built_in,rooms FROM buildings WHERE announcement_id = $id";
        }

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $row = oci_fetch_assoc($stid);
        $row = array_change_key_case($row, CASE_LOWER);
        if ($type === "house") {
            $row['floors'] = $row['floor'];
            unset($row['floor']);
        } elseif ($type === "apartment") {
            $row['apartmentType'] = $row['ap_type'];
            unset($row['ap_type']);
        }
        $row['builtIn'] = $row['built_in'];
        unset($row['built_in']);
        $row['parkingLots'] = $row['parking_lots'];
        unset($row['parking_lots']);

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }

    public function get_image($announcement_id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT name,type,image FROM images WHERE announcement_id = $announcement_id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $row = oci_fetch_assoc($stid);
        if ($row != false) {
            $row['IMAGE'] = $row['IMAGE']->load();
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }

    public function get_image_by_id($id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT name,type,image FROM images WHERE id = $id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $row = oci_fetch_assoc($stid);
        if ($row != false) {
            $row['IMAGE'] = $row['IMAGE']->load();
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }

    public function get_announcement_images_urls($announcement_id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id FROM images WHERE announcement_id = $announcement_id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $imagesURLs = [];
        $index = 0;

        while (($row = oci_fetch_assoc($stid)) != false) {
            $imagesURLs[$index] = "api/items/image?id=" . $row['ID'];
            $index++;
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $imagesURLs;
    }

    public function get_announcements_count()
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT count(*) FROM announcements";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        if (($count = oci_fetch($stid)) != false) {
            $count = oci_result($stid, 1);
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $count;
    }

    //TODO: name and type are useless, remove them
    public function add_image($announcement_id, $blob, $name, $type)
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
    public function check_existence_title($title, $id): bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM announcements WHERE title = $1 AND account_id = $2", array($title, $id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);

        return !!$row;
    }

    public function create_announcement(array $data)
    {
        $result = Model::save_data('announcements', $data, ['id']);

        return $result['id'];
    }

    public function get_close_located_items($latMin, $latMax, $lonMin, $lonMax)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id, lat, lon, price, type FROM announcements WHERE lat <= $latMax AND lat >= $latMin AND lon <= $lonMax AND lon >= $lonMin";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $data = [];

        while (($row = oci_fetch_assoc($stmt)) != false) {
            $row = array_change_key_case($row, CASE_LOWER);

            $row['imageURL'] = "api/items/image?announcement_id=" . $row['id'];
            array_push($data, $row);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();
        return $data;
    }

    public function delete_announcement_of_id($account_id, $announcement_id)
    {
        DatabaseConnection::get_connection();

        $sql = "DELETE FROM announcements WHERE account_id = $account_id AND id = $announcement_id";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);

        $result = oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();

        return $result;
    }
}
