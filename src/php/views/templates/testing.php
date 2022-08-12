<!-- TODO: Delete this -->

<?php
include_once DIR_BASE . "database/DatabaseConnection.php";
include_once DIR_MODELS . "account/AccountDM.php";

// $dbconn = DatabaseConnection::get_connection();

// $result = pg_query_params($dbconn, 'SELECT * FROM accounts WHERE email = $1', array("'1' OR 1 = 1"));


// $pg_error = pg_result_error($result);

// if($pg_error)
//     throw new InternalException($errors);

// $arr = pg_fetch_all($result);

// echo "<pre>";
// print_r($arr);
// echo "</pre>";
$data_mapper = new AccountDM();
echo $data_mapper->check_existence_email("george.butco@gmail.com", 6) ? "da" : "nu";