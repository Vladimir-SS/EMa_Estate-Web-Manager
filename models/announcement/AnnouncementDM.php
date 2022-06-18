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
            array_push($filters, " a.price > $temp");
        }

        if (isset($filter['priceMax'])) {
            $temp = $filter['priceMax'];
            array_push($filters, " a.price < $temp");
        }

        if (isset($filter['priceMax'])) {
            $temp = $filter['priceMax'];
            array_push($filters, " a.price < $temp");
        }

        if (isset($filter['transaction'])) {
            $temp = $filter['transaction'];
            array_push($filters, " a.transaction_type = '$temp'");
        }

        if (isset($filter['roomsMin'])) {
            $temp = $filter['roomsMin'];
            array_push($filters, " b.rooms > $temp");
        }

        if (isset($filter['roomsMax'])) {
            $temp = $filter['roomsMax'];
            array_push($filters, " b.rooms < $temp");
        }

        if (isset($filter['bathroomsMin'])) {
            $temp = $filter['bathroomsMin'];
            array_push($filters, " b.bathrooms > $temp");
        }

        if (isset($filter['bathroomsMax'])) {
            $temp = $filter['bathroomsMax'];
            array_push($filters, " b.bathrooms < $temp");
        }

        if (isset($filter['builtInMin'])) {
            $temp = $filter['builtInMin'];
            array_push($filters, " b.built_in > $temp");
        }

        if (isset($filter['builtInMax'])) {
            $temp = $filter['builtInMax'];
            array_push($filters, " b.built_in < $temp");
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

    public function find_id_by_account_id_and_title($account_id, $title): int
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id FROM announcements WHERE account_id = $account_id AND title LIKE '$title'";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        if (($row = oci_fetch($stid)) != false) {
            $row = oci_result($stid, 1);
        }
        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }

    public function add_image($announcement_id, $blob, $name, $type)
    {
        DatabaseConnection::get_connection();
        $sql = "INSERT INTO images (announcement_id, name, type, image) VALUES ($announcement_id, '$name','$type',EMPTY_BLOB()) RETURNING image INTO :image";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        $newlob = oci_new_descriptor(DatabaseConnection::$conn, OCI_D_LOB);
        oci_bind_by_name($stmt, ":image", $newlob, -1, OCI_B_BLOB);

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($newlob->save($blob)) {
            oci_commit(DatabaseConnection::$conn);
        } else {
            oci_rollback(DatabaseConnection::$conn);
        }

        $newlob->free();

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();
    }

    /**
     * Checks if the title already exists in the database
     * 
     * @param $title
     * @param $id
     * @return int|bool 1 if exists 0 if not | false in case of error
     */
    public function check_existence_title($title, $id): int|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT count(*) FROM announcements WHERE title='$title' AND account_id=$id";

        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        if (($row = oci_fetch($stmt)) != false) {
            $row = oci_result($stmt, 1);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();
        return $row;
    }

    public function create_announcement(array $data)
    {
        DatabaseConnection::get_connection();

        foreach ($data as $key => &$value) {
            $value["tag"] = ":$key" . "_bv";
        }

        $columns = [];
        $tags = [];

        foreach ($data as $key => &$value) {
            array_push($columns, $key);
            array_push($tags, $value["tag"]);
        }

        $sql = "INSERT INTO announcements (" . implode(",", $columns) . ") VALUES (" . implode(",", $tags) . ") RETURNING id INTO :id";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);

        foreach ($data as $key => &$value) {
            oci_bind_by_name($stmt, $value["tag"], $value["value"], -1, $value["type"]);
        }

        oci_bind_by_name($stmt, ":id", $id, -1, OCI_B_INT);

        oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();

        return $id;
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
