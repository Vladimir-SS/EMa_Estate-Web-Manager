<?php
include_once DIR_MODULES . "Module.php";

class AccountModule extends Module
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
                "business_name" => (new Column)
                    ->add(Constrain::Required)
            ]
        );
    }

    
}
