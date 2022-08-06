<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_MODELS . "Announcement/BuildingDM.php";

class BuildingModel extends Model
{
    public function __construct()
    {
        parent::__construct(
            [
                "ANNOUNCEMENT_ID" => (new Constraint()),
                "BATHROOMS" => (new Constraint())
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 99),
                "FLOOR" => (new Constraint())
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 99),
                "PARKING_LOTS" => (new Constraint())
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 99),
                "BUILT_IN" => (new Constraint())
                    ->add(Constrain::MinValue, 1950)
                    ->add(Constrain::MaxValue, 2024),
                "AP_TYPE" => (new Constraint())
                    ->add(Constrain::MinLength, 2)
                    ->add(Constrain::MaxLength, 32)
                    ->add(Constrain::SafeChars),
                "ROOMS" => (new Constraint())
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 99),
                "BASEMENT" => (new Constraint())
                    ->add(Constrain::MinValue, 0)
                    ->add(Constrain::MaxValue, 99)
            ]
        );
    }
}
