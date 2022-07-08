<?php

namespace COMPONENT\NEWS\CONTROLLER;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Type\DateTime;
use CBitrixComponent;
use CIBlockElement;
use CModule;

class Feedback extends Controller
{
    // Обязательный метод
    public function configureActions()
    {

        return [
            'sendClaim' => [ // Ajax-метод
                'prefilters' => [],
            ],
        ];
    }



    
    // Ajax-методы должны быть с постфиксом Action
    public function sendClaimAction($idNews)
    {

        $jsonObject = array();

        if (CModule::IncludeModule("iblock")) {


            global $USER;

            $arUser = '';

            if ($USER->IsAuthorized()) {
                $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
            } else {
                $arUser = "Пользователь не авторизован";
            }

            $arFields = array(
                "IBLOCK_ID" => 8,
                "NAME" => "Новость " . $idNews,
                "ACTIVE_FROM" => DateTime::createFromTimestamp(time()),
                "PROPERTY_VALUES" => array(
                    "USER" => $arUser,
                    "NEWS" => $idNews
                ),
            );

            $element = new CIBlockElement(false);
            if ($el = $element->Add($arFields)) {
                $jsonObject["ID"] = $el;
                return $jsonObject;
            }
        }
       // return null;
    }
}
