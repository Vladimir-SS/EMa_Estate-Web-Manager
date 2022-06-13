<?php
include_once DIR_MODELS . "Model.php";

class ProfileModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "LAST_NAME" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "FIRST_NAME" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "PHONE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MaxLength, 16)
                    ->add(Constrain::PhoneNumber),
                "EMAIL" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::Email)
                    ->add(Constrain::MaxLength, 64),
                "BUSINESS_NAME" => (new Constraint()),
                "CREATED_AT" => (new Constraint()),
                "OLD_PASSWORD" => (new Constraint())
                    ->add(Constrain::Required),
                "PASSWORD" => (new Constraint())
                    ->add(Constrain::MinLength, 8),
                "CONFIRM_PASSWORD" => (new Constraint())
                    ->add(Constrain::MinLength, 8),
            ]
        );
    }
}
