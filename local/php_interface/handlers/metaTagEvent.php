<?php
// файл /bitrix/php_interface/init.php
// регистрируем обработчик


namespace Local\Handlers;
 
IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Loader;
use CEventLog;
use CIBlockElement;

class MetaTagEvent
{
    static function onBeforePrologHandler()
    {

        global $APPLICATION;
        $urlPage = $APPLICATION->GetCurDir();

        if (Loader::includeModule("iBlock")) {
            $arFilter = array(
                "IBLOCK_ID" => 6,
                "NAME" => $urlPage,
            );

            $arSelect = array(
                "IBLOCK_ID",
                "ID",
                "PROPERTY_title",
                "PROPERTY_description"
            );

            $ob = CIBlockElement::GetList(
                array(),
                $arFilter,
                false,
                false,
                $arSelect
            );

            if ($res = $ob->Fetch()) {
                //echo '<pre>'; print_r($res); echo '</pre>';
                $APPLICATION->SetPageProperty('title',$res["PROPERTY_TITLE_VALUE"]);
                $APPLICATION->SetPageProperty('description',$res["PROPERTY_DESCRIPTION_VALUE"]);

            }

        }

        // echo '<pre>'; print_r($event); echo '</pre>';
        // echo '<pre>'; print_r($lid); echo '</pre>';
        // echo '<pre>'; print_r($arFields); echo '</pre>';
            
    }
}