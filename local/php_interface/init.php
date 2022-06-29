<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

$eventManager = EventManager::getInstance();

Loader::registerNamespace("Local\Handlers",Loader::getLocal('php_interface/handlers'));
//Loader::registerNamespace("Local\Entity",Loader::getLocal('php_interface/classes'));

if (file_exists(Loader::getLocal('php_interface/handlers.php'))) {
    require_once (Loader::getLocal('php_interface/handlers.php'));
}


// require_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/autoload.php");

// AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("ActiveEvent", "onBeforeIBlockElementUpdate"));
// AddEventHandler("main", "OnEpilog", Array("NotFoundEvent", "pageNotFound"));

// if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/active_event.php")) {
//     include_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/active_event.php");
// }

// if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/not_found_event.php")) {
//     require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/not_found_event.php");
// }