<?php
include_once DIR_BASE . "/db_config.php";

class DatabaseConnection
{
    public static $conn = NULL;

    private function __construct()
    {
    }

    public static function get_connection()
    {
        if (is_null(DatabaseConnection::$conn)) {
            DatabaseConnection::$conn = oci_connect(DB_USER, DB_PASS);
            if (!DatabaseConnection::$conn) {
                $e = oci_error();
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
        }
        return DatabaseConnection::$conn;
    }

    // private static function createBindTags(array &$data): void
    // {
    //     foreach ($data as $key => &$value) {
    //         $value["tag"] = ":$key" . "_bv";
    //     }
    // }

    // public static function dataSave(string $tableName, array &$data): DatabaseConnection
    // {
    //     self::createBindTags($data);

    //     $columns = [];
    //     $tags = [];

    //     foreach ($data as $key => &$value) {
    //         array_push($columns, $key);
    //         array_push($tags, $value["tag"]);
    //     }

    //     $sql = "INSERT into $tableName (" . implode(",", $columns) . ") VALUES (" . implode(",", $tags) . ")";
    //     echo $sql;
    //     $stid = oci_parse(self::$conn, $sql);

    //     foreach ($data as $key => &$value) {
    //         oci_bind_by_name($stid, $value["tag"], $value["value"], -1, $value["type"]);
    //     }
    //     oci_execute($stid);

    //     $errors = oci_error(self::$conn);

    //     if ($errors) {
    //         echo "<pre>";
    //         var_dump($errors);
    //         echo "</pre>";
    //     }

    //     return self::$instance;
    // }

    // public static function dataFindByID(string $tableName, int $id): array|false
    // {
    //     $sql = "SELECT * FROM $tableName where id=$id";
    //     $stid = oci_parse(self::$conn, $sql);
    //     oci_execute($stid);

    //     $row = oci_fetch_array($stid, OCI_ASSOC);
    //     oci_free_statement($stid);

    //     return $row;
    // }

    public static function close(): bool
    {
        $closed = !self::$conn ? true : oci_close(self::$conn);
        self::$conn = null;
        return $closed;
    }
}
