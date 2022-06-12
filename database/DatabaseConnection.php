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
