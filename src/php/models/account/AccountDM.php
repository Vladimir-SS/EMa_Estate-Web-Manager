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
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT last_name, first_name, email, phone, business_name, created_at FROM accounts WHERE id = $1";
        $result = pg_query_params($dbconn,  $sql, array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);
        return $row;
    }

    public function get_image($id)
    {
        $dbconn = DatabaseConnection::get_connection();
        $sql = "SELECT image_type,image FROM accounts WHERE id = $1";
        $result = pg_query_params($dbconn,  $sql, array($id));

        $pg_error = pg_result_error($result);
        if ($pg_error)
            throw new InternalException($pg_error);

        $row = pg_fetch_assoc($result);
        if(!$row) return false; 
        $row['image'] = pg_unescape_bytea($row['image']);
        return $row;
    }

    public function update_account_data($id, array $data)
    {
        Model::update_data_by_id('accounts', $id, $data);
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
                return $id !== (int) $row[0];

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
                return $id !== (int) $row[0];

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
