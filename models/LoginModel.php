<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "account/AccountDM.php";
include_once DIR_MODELS . "account/AccountService.php";

class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "email_or_phone" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::EmailOrPhone)
                    ->add(Constrain::MinLength, 4)
                    ->add(Constrain::MaxLength, 64),
                "password" => (new Constraint())
                    ->add(Constrain::Required)
            ]
        );
    }
}
