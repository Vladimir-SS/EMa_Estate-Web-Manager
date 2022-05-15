<?php
include_once DIR_MODULES . "Column.php";

class Module
{
    private array $columns;
    private array $data;

    protected function __construct(array $columns)
    {
        $this->columns = $columns;
        $this->data = [];
    }

    public function load(array $data): Module
    {
        $this->data = $data;

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
}
