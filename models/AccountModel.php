<?php
include_once DIR_MODELS . "Model.php";

class AccountModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "name" => (new Column())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 6)
                    ->add(Constrain::MaxLength, 64),
                "phone" => (new Column())
                    ->add(Constrain::Required)
                    ->add(Constrain::MaxLength, 16)
                    ->add(Constrain::PhoneNumber),
                "email" => (new Column())
                    ->add(Constrain::Required)
                    ->add(Constrain::Email)
                    ->add(Constrain::MaxLength, 64),
                "password_hash" => (new Column)
                    ->add(Constrain::Required),
                "business_name" => (new Column),
                "password_salt" => (new Column)
            ]
        );
    }

    public function generateSalt(): AccountModel
    {
        $this->data["password_salt"] = random_bytes(4);

        return $this;
    }


    public function generateHash(string $password): AccountModel
    {
        if (!isset($this->data["password_salt"]))
            throw new Exception("wrong password");

        $whole = $password . $this->data["password_salt"] . ACCOUNT_PEPPER;


        $this->data["password_hash"] = password_hash($whole, PASSWORD_DEFAULT);

        return $this;
    }

    public function password_check(string $password): bool
    {
        $whole = $password . $this->data["password_salt"] . ACCOUNT_PEPPER;

        return password_verify($whole, $this->data["password_hash"]);;
    }
}
