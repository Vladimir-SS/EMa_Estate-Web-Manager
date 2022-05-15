<?php

include_once DIR_MODULES . "AccountModule.php";

$module = new AccountModule();

$result = $module->load(["name" => "hwwwww", "phone" => "0744704733", "email" => "george.butco@gmail.co"])->validate();

echo "<pre>";
var_dump($result);
echo "</pre>";
