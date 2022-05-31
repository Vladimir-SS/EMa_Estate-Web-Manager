<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "Announcement/AnnouncementDM.php";

class AnnouncementModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "account_id" => (new Constraint())
                    ->add(Constrain::Required),
                "title" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 4)
                    ->add(Constrain::MaxLength, 64)
                    ->add(Constrain::SafeChars),
                "price" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 9999999),
                "surface" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 999999),
                "address" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 6)
                    ->add(Constrain::MaxLength, 128)
                    ->add(Constrain::SafeChars),
                "transaction_type" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 2)
                    ->add(Constrain::MaxLength, 64),
                "description" => (new Constraint())
                    ->add(Constrain::Required)
                    ->add(Constrain::MinLength, 10)
                    ->add(Constrain::MaxLength, 4000)
                    ->add(Constrain::SafeChars)
            ]
        );
    }
}
