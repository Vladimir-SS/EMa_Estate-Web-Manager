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
                if (empty($value)) return "Câmpul este necesar";
                break;

            case true:
                if (empty($value))
                    return null;

            case Constrain::MinValue:
                if ($params[0] > $value) return "Valoare sub limita minimă ( minim:$params[0])";
                break;

            case Constrain::MaxValue:
                if ($params[0] < $value) return "Valoare peste limita maximă ( maxim:$params[0])";
                break;

            case Constrain::MinLength:
                if (strlen($value) < $params[0]) return "Text prea scurt ( minim:$params[0])";
                break;

            case Constrain::MaxLength:
                if (strlen($value) > $params[0]) return "Text prea lung ( maxim: $params[0])";
                break;

            case Constrain::PhoneNumber:
                if (!preg_match("/^\d{10}$/", $value)) return "Număr de telefon valid ( Ex valid: 0744 123 123)";
                break;

            case Constrain::Email:
                if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $value)) return "Email valid ( Ex valid: abc@mail.com)";
                break;

            case Constrain::EmailOrPhone:
                if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $value)) {
                    if (!preg_match("/^\d{10}$/", $value))
                        return "Email sau număr de telefon invalid";
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
    //private int $type;

    public function __construct()
    {
        $this->constraints = [];
        
        //TODO: constraint depending on type
        //$this->type = SQLT_CHR;
        $this->type = 1;
    }

    // public function setType(int $type)
    // {
    //     $this->type = $type;
    // }

    // public function getType(): int
    // {
    //     return $this->type;
    // }

    public function add(Constrain $c, ...$params): Constraint
    {
        array_push($this->constraints, [$c, $params]);
        return $this;
    }

    public function validate(?string $val): ?string
    {
        $required = false;

        foreach ($this->constraints as [$c, $params]) {
            if (Constrain::Required == $c) {
                $required = true;
                break;
            }
        }

        if (!$required && empty($val))
            return null;

        foreach ($this->constraints as [$c, $params]) {
            $s = Constrain::run($c, $val, ...$params);

            if (!empty($s))
                return $s;
        }

        return null;
    }
}
