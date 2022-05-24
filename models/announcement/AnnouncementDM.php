<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "Announcement/AnnouncementDO.php";
include_once DIR_BASE . "database/DatabaseConnection.php";

class AnnouncementDM
{

    public function __construct()
    {
    }

    public function findIdByAccountIdAndTitle($account_id, $title): int
    {
        DatabaseConnection::getConnection();
        $sql = "SELECT id FROM announcements WHERE account_id = $account_id AND title LIKE '$title'";

        $stid = oci_parse(DatabaseConnection::$conn, $sql);
        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }

        if (($row = oci_fetch($stid)) != false) {
            $row = oci_result($stid, 1);
        }
        oci_free_statement($stid);
        // DatabaseConnection::close();
        return $row;
    }

    public function addImage($announcement_id, $blob, $name, $type)
    {
        DatabaseConnection::getConnection();
        $sql = "INSERT INTO images (announcement_id, name, type, image) VALUES ($announcement_id, '$name','$type',EMPTY_BLOB()) RETURNING image INTO :image";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        $newlob = oci_new_descriptor(DatabaseConnection::$conn, OCI_D_LOB);
        oci_bind_by_name($stmt, ":image", $newlob, -1, OCI_B_BLOB);

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        // and just to make it more clear
        if ($newlob->save($blob)) {
            oci_commit(DatabaseConnection::$conn);
        } else {
            oci_rollback(DatabaseConnection::$conn);
        }

        $newlob->free();

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }
        oci_free_statement($stmt);
    }

    public function createAnnouncement(array &$data)
    {
        DatabaseConnection::getConnection();

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
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }
        oci_free_statement($stid);
    }
}
