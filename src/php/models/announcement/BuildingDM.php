<?php
include_once DIR_MODELS . "Model.php";
include_once DIR_CORE . "exceptions/InternalException.php";

class BuildingDM
{
    /**
     * Creates new building in database
     * 
     * @param $data array
     */
    public function create_building(array $data)
    {
        Model::save_data('buildings', $data);
    }
}
