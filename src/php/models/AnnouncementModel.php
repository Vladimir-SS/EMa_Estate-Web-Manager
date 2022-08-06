<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "Announcement/AnnouncementDM.php";

class AnnouncementModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "ACCOUNT_ID" => (new Constraint())
                    ->add(Constrain::Required),
                "TITLE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 4)
                    ->add(Constrain::MaxLength, 64)
                    ->add(Constrain::SafeChars),
                "PRICE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 9999999),
                "SURFACE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 999999),
                "ADDRESS" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 6)
                    ->add(Constrain::MaxLength, 128)
                    ->add(Constrain::SafeChars),
                "LAT" => (new Constraint()),
                "LON" => (new Constraint()),
                "TRANSACTION_TYPE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 2)
                    ->add(Constrain::MaxLength, 64),
                "DESCRIPTION" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 10)
                    ->add(Constrain::MaxLength, 3000)
                    ->add(Constrain::SafeChars),
                "TYPE" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 2)
                    ->add(Constrain::MaxLength, 32)
                    ->add(Constrain::SafeChars),
            ]
        );
    }
}
