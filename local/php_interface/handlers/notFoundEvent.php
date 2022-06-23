<?php
// файл /bitrix/php_interface/init.php
// регистрируем обработчик
namespace Local\Handlers;

use CEventLog;

class NotFoundEvent
{
    static function pageNotFound()
    {
        //echo 1232142141421421;
        if (defined("ERROR_404") && ERROR_404 == "Y") {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php";
            include $_SERVER["DOCUMENT_ROOT"]."/404.php";
            include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/footer.php";
            CEventLog::Add(
                array(
                    "SEVERRITY" => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => $APPLICATION->GetCurPage()
                )
            );
        }
            
    }
}