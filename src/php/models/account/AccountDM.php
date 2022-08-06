<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

class AccountDM
{

    public function __construct()
    {
    }
    public function get_data_by_id($id): array| bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT last_name, first_name, email, phone, business_name, created_at FROM accounts WHERE id = $id";

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

    public function get_image($id)
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT image_type,image FROM accounts WHERE id = $id";

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

    public function update_account_data($id, array $data)
    {
        DatabaseConnection::get_connection();

        foreach ($data as $key => &$value) {
            $value["tag"] = ":$key" . "_bv";
        }

        $sql = "UPDATE accounts SET " . implode(
            ", ",
            array_map(
                function ($k, $v) {
                    return $v["value"] ? ($k . "= " . $v["tag"]) : (($k === 'BUSINESS_NAME') ? ($k . "= NULL") : ($k . "= " . $k));
                },
                array_keys($data),
                array_values($data)
            )
        ) . " WHERE id=$id";
        $stid = oci_parse(DatabaseConnection::$conn, $sql);

        foreach ($data as $key => &$value) {
            if ($value['value']) {
                oci_bind_by_name($stid, $value["tag"], $value["value"], -1, $value["type"]);
            }
        }

        oci_execute($stid);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }

        oci_free_statement($stid);
        DatabaseConnection::close();
    }

    public function update_account_image($id, $image)
    {
        DatabaseConnection::get_connection();
        $sql = "UPDATE accounts SET image = EMPTY_BLOB() WHERE id = $id RETURNING image INTO :image";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);
        $newlob = oci_new_descriptor(DatabaseConnection::$conn, OCI_D_LOB);
        oci_bind_by_name($stmt, ":image", $newlob, -1, OCI_B_BLOB);

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($newlob->save($image)) {
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
            throw new InternalException($errors);
        }

        if (($row = oci_fetch($stid)) != false) {
            $row = oci_result($stid, 1);
        }
        oci_free_statement($stid);
        DatabaseConnection::close();
        return $row;
    }

    /**
     * Finds password salt by id
     * 
     * @param $id
     * @return string|bool $salt | false
     */
    public function get_salt_by_id($id): string|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT password_salt FROM accounts WHERE id=$id";

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
    /**
     * Finds password by id
     * 
     * @param $id
     * @return string|bool $password | false
     */
    public function get_password_by_id($id): string|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT password FROM accounts WHERE id=$id";

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

    /**
     * Checks if the email exists in the database and the id is different if it exists
     * 
     * @param mixed $email
     * @param $id
     * @return int|bool 1 if exists 0 if not | false in case of error
     */
    public function check_existence_email($email, $id = -1): int|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT count(*) FROM accounts WHERE email='$email'" . (($id != -1) ? " AND id!= $id" : '');;

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

    /**
     * Checks if the phone number exists in the database and the id is different if it exists
     * 
     * @param mixed $phone
     * @param $id
     * @return int|bool 1 if exists 0 if not | false in case of error
     */
    public function check_existence_phone($phone, $id = -1): int|bool
    {
        DatabaseConnection::get_connection();
        $sql = "SELECT count(*) FROM accounts WHERE phone='$phone'" . (($id != -1) ? " AND id!= $id" : '');
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
            throw new InternalException($errors);
        }
        oci_free_statement($stid);
        DatabaseConnection::close();
    }
}
