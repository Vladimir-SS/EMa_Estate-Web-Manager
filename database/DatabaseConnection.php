<?php
include_once DIR_BASE . "/db_config.php";

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private static $conn;

    private function __construct()
    {
        $conn = oci_connect(DB_USER, DB_PASS) or throw new Exception('Unable to connect');

        self::$conn = $conn;
    }

    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        }

        return self::$instance;
    }

    public static function getConnection()
    {
        return self::$conn;
    }

    public static function close(): bool
    {
        if (!self::$conn)
            return true;
        return oci_close(self::$conn);
    }

    function __destructor()
    {
    }
}
