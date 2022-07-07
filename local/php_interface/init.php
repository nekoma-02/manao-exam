<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Local\Agents\RegisterAgent;

$eventManager = EventManager::getInstance();

Loader::registerNamespace("Local\Handlers",Loader::getLocal('php_interface/handlers'));

if (file_exists(Loader::getLocal('php_interface/handlers.php'))) {
    require_once (Loader::getLocal('php_interface/handlers.php'));
}

Loader::registerNamespace("Local\Agents",Loader::getLocal('php_interface/classes'));

if (file_exists(Loader::getLocal('php_interface/agents.php'))) {
    require_once (Loader::getLocal('php_interface/agents.php'));
}
