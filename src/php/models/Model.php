<?php
include_once DIR_BASE . "database/DatabaseConnection.php";
include_once DIR_MODELS . "Constraint.php";

//This should be ModelDM (model data manager)
class Model
{
    private array $columns;
    public array $data;
    public array $errors = [];

    protected function __construct(array $columns)
    {
        $this->columns = $columns;
        foreach ($this->columns as $key => $value) { // load empty data
            $this->data[$key] = null;
        }
    }

    public function load(array $data): Model
    {
        //$this->data = [];
        foreach ($data as $key => &$value) {
            if (isset($this->columns[$key])) {
                $this->data[$key] = $value;
            }
        }

        return $this;
    }

    public function validate()
    {
        foreach ($this->columns as $attribute => $column) {

            $s = $column->validate($this->data[$attribute] ?? null);

            if (!empty($s))
                $this->errors[$attribute] = $s; // array_push($this->errors, [$attribute => $s]);
        }

        return empty($this->errors);
    }

    public function get_data(): array
    {
        //TODO: deal with this after type def
        // $data = [];

        // foreach ($this->data as $key => $value) {
        //     $data[$key] = [
        //         "type" => $this->columns[$key]->getType(),
        //         "value" => $value
        //     ];
        // }
        // return $data;

        return $this->data;
    }

    public function has_errors($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    static public function save_data($table_name, $data, $return_list = null): array{
        $column_names = array_keys($data);
        $values = array_map(fn($x) => '$'.$x, range(1, count($column_names)));
        $sql = "INSERT INTO $table_name (" . implode(",", $column_names) . ") VALUES (" . implode(",", $values) . ")";

        if($return_list){
            $return_string = implode(",", $return_list);
            $sql .= " RETURNING $return_string";
        }
        
        $dbconn = DatabaseConnection::get_connection();
        $result = pg_query_params($dbconn, $sql, array_map(fn($key)=>$data[$key], $column_names));

        if(!$return_list) return array();
        $row = pg_fetch_row($result);
        $rv = array_combine($return_list, $row);

        return $rv;
    }
}
