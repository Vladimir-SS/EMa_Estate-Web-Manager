<?php
include_once DIR_BASE . "db_config.php";
include_once DIR_BASE . "database/DatabaseConnection.php";
include_once DIR_MODELS . "Constraint.php";

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
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[$key] = [
                "type" => $this->columns[$key]->getType(),
                "value" => $value
            ];
        }
        return $data;
    }

    public function has_errors($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }
}
