<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

class AnnouncementDM
{

    public function __construct()
    {
    }

    public function get_announcements($count, $index = 0)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id,account_id,title,price,surface,address,transaction_type,description,land,created_at,updated_at FROM (SELECT rownum AS rn, a.* FROM announcements a) WHERE rn > $index AND rn <= $index+$count";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $data = [];

        for ($i = 0; $i <= $count; $i++) {
            if (($row = oci_fetch_assoc($stid)) != false) {
                $row['IMAGE'] = $this->get_image($row['ID']);
                if ($row['LAND'] == 0) {
                    $row['BUILDING'] = $this->get_building($row['ID']);
                }
                $data[$i] = $row;
            }
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $data;
    }

    public function get_image($id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT name,type,image FROM images WHERE announcement_id = $id";

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

    public function get_building($id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT * FROM buildings WHERE announcement_id = $id";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        $row = oci_fetch_assoc($stid);

        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
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
     * @return int|bool 1 if exists 0 if not | false in case of error
     */
    public function check_existence_title($title): int|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT count(*) FROM announcements WHERE title='$title'";

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

        $sql = "INSERT INTO announcements (" . implode(",", $columns) . ") VALUES (" . implode(",", $tags) . ")";
        $stid = oci_parse(DatabaseConnection::$conn, $sql);

        foreach ($data as $key => &$value) {
            oci_bind_by_name($stid, $value["tag"], $value["value"], -1, $value["type"]);
        }
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        oci_free_statement($stid);
        DatabaseConnection::close();
    }
}
