<?php
include_once DIR_BASE . "database/db_config.php";

class DatabaseConnection
{
    public static $conn = NULL;

    public static function get_connection()
    {
        if (is_null(DatabaseConnection::$conn)) {
            DatabaseConnection::$conn = pg_connect(
                "host=". DB_ADDRESS .
                " port=5432 dbname=" . DB_NAME .
                " user=" . DB_USER .
                " password=" . DB_PASS
            ) or trigger_error(htmlentities("Could not connect to the database!", ENT_QUOTES), E_USER_ERROR);
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
