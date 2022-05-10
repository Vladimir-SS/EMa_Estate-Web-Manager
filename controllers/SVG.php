<?php
$file_path = DIR_SVG . $file_name;
if (file_exists($file_path))
    include_once $file_path;
