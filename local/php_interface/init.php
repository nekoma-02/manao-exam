<?php
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/active_event.php")) {
    include_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/active_event.php");
}

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/not_found_event.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/not_found_event.php");
}