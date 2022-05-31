<?php
include_once DIR_MODELS . "Model.php";

class AccountDM
{

    public function __construct()
    {
    }

    /**
     * Finds id by email or phone
     *
     * @param  mixed $email_or_phone
     * @return int|bool $id | false
     */
    public function find_id_by_email_or_phone(string $email_or_phone): int|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT id FROM accounts WHERE email LIKE '$email_or_phone' OR phone LIKE '$email_or_phone'";

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
        DatabaseConnection::close();
        return $row;
    }

    public function getIdSalt($id): string|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT password_salt FROM accounts WHERE id=$id";

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
        DatabaseConnection::close();
        return $row;
    }

    public function getPasswordById($id): string|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT password FROM accounts WHERE id=$id";

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
        DatabaseConnection::close();
        return $row;
    }

    /**
     * Saves account data in the database
     *
     * @param  mixed $data
     * @return void
     */
    public function register_save(array $data)
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

        $sql = "INSERT INTO accounts (" . implode(",", $columns) . ") VALUES (" . implode(",", $tags) . ")";
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
        DatabaseConnection::close();
    }
}
