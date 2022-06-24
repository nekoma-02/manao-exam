<?php

$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("Local\Handlers\ActiveEvent", "onBeforeIBlockElementUpdate"));
$eventManager->addEventHandler("main", "OnEpilog", array("Local\Handlers\NotFoundEvent", "pageNotFound"));
$eventManager->addEventHandler("main", "OnBeforeEventAdd", array("Local\Handlers\FeedbackEvent", "onBeforeEventAddHandler"));
$eventManager->addEventHandler("main", "OnBuildGlobalMenu", array("Local\Handlers\BuildMenuEvent", "onBuildGlobalMenu"));