<?php
$myfile = fopen("testfile.txt", "w");
include_once DIR_CONTROLLERS . "FileRequest.php";

FileRequest::respond(DIR_VIEWS . 'font/' . $file_name);
