<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

//TODO: this can be static
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
     * @param string $email_or_phone
     * @return int|bool $id | false
     */
    public function find_id_by_email_or_phone(string $email_or_phone): int|bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM accounts WHERE email = $1 OR phone = $1", array($email_or_phone));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);

        if($row) return $row[0];

        return false;
    }

    //TODO: I think u can get all the information by email or phone, you don't need 20 functions

    /**
     * Finds password salt by id
     * 
     * @param $id
     * @return string|bool $salt | false
     */
    public function get_salt_by_id($id): string|bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,   "SELECT password_salt FROM accounts WHERE id = $1", array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);

        if($row){
            echo $row[0] . "</br>";
            return pg_unescape_bytea($row[0]);
        }

        return false;
    }
    /**
     * Finds password by id
     * 
     * @param $id
     * @return string|bool $password | false
     */
    public function get_password_by_id($id): string|bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,   "SELECT password FROM accounts WHERE id = $1", array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);

        if($row) return $row[0];

        return false;
    }
 
    /**
     * Checks if the email exists in the database and the id is different if it exists
     *
     * @param  string $email
     * @param  int $id
     * @return bool true if exists and false otherwise
     */
    public function check_existence_email(string $email, int $id = null): bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM accounts WHERE email = $1", array($email));
        
        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);
        if($row){
            if($id)
                return $id === (int) $row[0];

            return true;
        }

        return false;
    }

    /**
     * Checks if the phone number exists in the database and the id is different if it exists
     * 
     * @param string $phone
     * @param int $id
     * @return bool true if exists and false otherwise
     */
    public function check_existence_phone(string $phone, int $id = null): bool
    {
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn,  "SELECT id FROM accounts WHERE phone = $1", array($phone));
        
        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_row($result);
        if($row){
            if($id)
                return $id === (int) $row[0];

            return true;
        }

        return false;
    }

    /**
     * Saves account data in the database
     *
     * @param  mixed $data
     * @return void
     */
    public function register_save(array $data)
    {
        $dbconn = DatabaseConnection::get_connection();
        $data["password_salt"] = pg_escape_bytea($dbconn, $data["password_salt"]);

        Model::save_data('accounts', $data);
    }
}
