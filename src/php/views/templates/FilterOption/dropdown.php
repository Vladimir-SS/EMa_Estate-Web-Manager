<?php
$option_classes = "filter-option--dropdown";
$option_content = "";
foreach ($option_list as $option_item)
    $option_content .= "<li onclick=\"OptionHandler.chooseThis(this)\">$option_item</li>";
$option_vector = "dropdown-arrow";
if (is_numeric($option_name))
    $option_name = $option_list[$option_name];
