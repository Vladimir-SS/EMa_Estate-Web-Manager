<?php
include_once DIR_BASE . "database/db_config.php";

class DatabaseConnection
{
    public static $conn = NULL;

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

    public static function close(): bool
    {
        $closed = !self::$conn ? true : oci_close(self::$conn);
        self::$conn = null;
        return $closed;
    }
}
