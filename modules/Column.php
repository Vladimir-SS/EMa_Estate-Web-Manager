<?php

enum Constrain
{
    case MinValue;
    case MaxValue;
    case Required;
    case MaxLength;
    case MinLength;
    case PhoneNumber;
    case Email;

    public static function run(Constrain $c, ?string $value, ...$params): ?string
    {
        switch ($c) {
            case Constrain::Required:
                if (empty($value)) return "este necesar";
                break;

            case true:
                if (empty($value))
                    return null;

            case Constrain::MinValue:
                if ($params[0] > $value) return "valoarea minimă este $params[0]";
                break;

            case Constrain::MaxValue:
                if ($params[0] < $value) return "valoarea maximă este $params[0]";
                break;

            case Constrain::MinLength:
                if (strlen($value) < $params[0]) return "dimensiunea minimă este $params[0]";
                break;

            case Constrain::MaxLength:
                if (strlen($value) > $params[0]) return "dimensiunea maximă este $params[0]";
                break;

            case Constrain::PhoneNumber:
                if (!preg_match("/^\d{10}$/", $value)) return "exemplu: 0744 123 123";
                break;

            case Constrain::Email:
                if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $value)) return "exemplu: popescu.ionel@gmail.com";
                break;
        }

        return null;
    }
}

class Column
{
    private array $constraints;

    public function __construct()
    {
        $this->constraints = [];
    }

    public function add(Constrain $c, ...$params): Column
    {
        array_push($this->constraints, [$c, $params]);
        return $this;
    }

    public function validate(?string $val): ?string
    {

        foreach ($this->constraints as [$c, $params]) {
            $s = Constrain::run($c, $val, ...$params);

            if (!empty($s))
                return $s;
        }

        return null;
    }
}
