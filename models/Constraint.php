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
    case EmailOrPhone;
    case SafeChars;

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
                if (!preg_match("/^\d{10}$/", $value)) return "Numarul de telefon nu este valid (Exemplu valid: 0744 123 123)";
                break;

            case Constrain::Email:
                if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $value)) return "Mail-ul nu este valid (Exemplu valid: popescu.ionel@gmail.com)";
                break;

            case Constrain::EmailOrPhone:
                if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $value)) {
                    if (!preg_match("/^\d{10}$/", $value))
                        return "Mail-ul sau numarul de telefon introdus nu este valid";
                }
                break;

            case Constrain::SafeChars:
                if (!preg_match("/^[^'*%]*$/", $value)) return "Datele introduse nu pot contine caracterele ', * sau %";
                break;
        }

        return null;
    }
}

class Constraint
{
    private array $constraints;
    private int $type;

    public function __construct()
    {
        $this->constraints = [];
        $this->type = SQLT_CHR;
    }

    public function setType(int $type)
    {
        $this->type = $type;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function add(Constrain $c, ...$params): Constraint
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
