<?php
include_once DIR_BASE . "db_config.php";
include_once DIR_BASE . "database/DatabaseConnection.php";

class Model
{
    private array $columns;
    public array $data;

    protected function __construct(array $columns)
    {
        $this->columns = $columns;
        $this->data = [];
    }

    public function load(array $data): Model
    {
        $this->data = [];
        foreach ($data as $key => &$value) {
            if (isset($this->columns[$key])) {
                $this->data[$key] = $value;
            }
        }

        return $this;
    }

    public function validate(): array
    {
        $rv = array();

        foreach ($this->columns as $name => $column) {

            $s = $column->validate($this->data[$name] ?? null);

            if (!empty($s))
                array_push($rv, [$name => $s]);
        }

        return $rv;
    }

    public function getData(): array
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
}
