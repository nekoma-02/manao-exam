<?php

namespace Local\Handlers;

use CIBlockElement;

define("ID_BLOCK_PRODUCT",2);

class ActiveEvent
{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    static function onBeforeIBlockElementUpdate(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == ID_BLOCK_PRODUCT) {
            if ($arFields["ACTIVE"] == "N") {
                $arSelect = array(
                    "ID", 
                    "IBLOCK_ID", 
                    "NAME",
                    "SHOW_COUNTER"
                );//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
            
                $arFilter = array(
                    "IBLOCK_ID"=>ID_BLOCK_PRODUCT, 
                    "ID"=>$arFields["ID"], 
                );
            
                $res = CIBlockElement::GetList(
                    array(), 
                    $arFilter, 
                    false, 
                    false, 
                    $arSelect
                );
            
                $ob = $res->Fetch();
                 

                if ($ob["SHOW_COUNTER"]>2) {

                    global $APPLICATION;
                    $APPLICATION->throwException("Товар невозможно деактивировать, у него ". $ob["SHOW_COUNTER"] . " просмотров");
                    return false;
                }

            
            }
        }
    }
}