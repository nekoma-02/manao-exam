<?php
// файл /bitrix/php_interface/init.php
// регистрируем обработчик


namespace Local\Handlers;
 
IncludeModuleLangFile(__FILE__);
use CEventLog;

class FeedbackEvent
{
    static function onBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {

        if ($event == "FEEDBACK_FORM") {
            global $USER;
            if ($USER->isAuthorized()) {
                $arFields["AUTHOR"] = "Пользователь авторизован";
                // GetMessage("AUTH_USER", array(
                //     "#ID#"=>$USER->GetID(),
                //     "#LOGIN#"=>$USER->GetLogin(),
                //     "#NAME#"=>$USER->GetFullName(),
                //     "#NAME_FORM"=>$arFields["AUTHOR"]

                // ));
            } else {
                $arFields["AUTHOR"] = "Пользователь не авторизован"
                // GetMessage("NOT_AUTH_USER", array(
                //     "#NAME_FORM"=>$arFields["AUTHOR"]
                // ));
            }
        } 

        CEventLog::Add(array(
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => "Замена данных в отсылаемом письме",
            "MODULE_ID" => "main",
            "ITEM_ID" => $event,
            "DESCRIPTION" => "Замена данных в отсылаемом письме" . $arFields["AUTHOR"],
        ));

        // echo '<pre>'; print_r($event); echo '</pre>';
        // echo '<pre>'; print_r($lid); echo '</pre>';
        // echo '<pre>'; print_r($arFields); echo '</pre>';
            
    }
}