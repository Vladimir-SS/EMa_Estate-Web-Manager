<?php
include_once DIR_MODELS . "Model.php";

class ProfileModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "last_name" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "first_name" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 3)
                    ->add(Constrain::MaxLength, 32),
                "phone" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MaxLength, 16)
                    ->add(Constrain::PhoneNumber),
                "email" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::Email)
                    ->add(Constrain::MaxLength, 64),
                "business_name" => (new Constraint()),
                "created_at" => (new Constraint()),
                "old_password" => (new Constraint())
                    ->add(Constrain::Required),
                "password" => (new Constraint())
                    ->add(Constrain::MinLength, 8),
                "confirm_password" => (new Constraint())
                    ->add(Constrain::MinLength, 8),
            ]
        );
    }
}
