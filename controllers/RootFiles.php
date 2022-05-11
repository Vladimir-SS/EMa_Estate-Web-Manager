<?php
include_once DIR_CONTROLLERS . "FileRequest.php";

FileRequest::respond(DIR_BASE . 'resources/' . $file_name);
