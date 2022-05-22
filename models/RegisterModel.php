<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "account/AccountDO.php";
include_once DIR_MODELS . "account/AccountDM.php";
include_once DIR_MODELS . "account/AccountService.php";

class RegisterModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "last_name" => (new AccountDO())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "first_name" => (new AccountDO())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "phone" => (new AccountDO())
                    ->add(Constrain::Required)
                    ->add(Constrain::MaxLength, 16)
                    ->add(Constrain::PhoneNumber),
                "email" => (new AccountDO())
                    ->add(Constrain::Required)
                    ->add(Constrain::Email)
                    ->add(Constrain::MaxLength, 64),
                "password" => (new AccountDO)
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 8),
                "password_salt" => (new AccountDO)
            ]
        );
    }
}
