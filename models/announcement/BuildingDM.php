<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

class BuildingDM
{

    public function __construct()
    {
    }

    public function create_building(array $data)
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

        $sql = "INSERT INTO buildings (" . implode(",", $columns) . ") VALUES (" . implode(",", $tags) . ")";
        $stmt = oci_parse(DatabaseConnection::$conn, $sql);

        foreach ($data as $key => &$value) {
            oci_bind_by_name($stmt, $value["tag"], $value["value"], -1, $value["type"]);
        }

        oci_execute($stmt);

        $errors = oci_error(DatabaseConnection::$conn);

        if ($errors) {
            throw new InternalException($errors);
        }
        oci_free_statement($stmt);
        DatabaseConnection::close();
    }
}
