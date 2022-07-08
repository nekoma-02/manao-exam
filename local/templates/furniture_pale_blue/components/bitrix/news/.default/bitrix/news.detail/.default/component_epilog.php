<?php

//echo '<pre>'; print_r($arResult); echo '</pre>';

use Bitrix\Main\Type\DateTime;

if (isset($arResult["REF_CANONICAL"])) {
    $APPLICATION->SetPageProperty("canonical", $arResult["REF_CANONICAL"]);
}

if ($arParams["REPORT_AJAX"] == "N") {

    if (isset($_GET["ID"])) {
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
                "NAME" => "Новость " . $_GET["ID"],
                "ACTIVE_FROM" => DateTime::createFromTimestamp(time()),
                "PROPERTY_VALUES" => array(
                    "USER" => $arUser,
                    "NEWS" => $_GET["ID"],
                ),
            );

            $element = new CIBlockElement(false);
            if ($el = $element->Add($arFields)) {
                $jsonObject["ID"] = $el;
                echo '
                    <script>
                        var textElem = document.getElementById("ajax-report-text");
                        textElem.innerText = "Ваше мнение учтено!";
                        window.history.pushState(null,null,"'.$APPLICATION->GetCurPage().'");
                    </script>
                ';
            }
        }
    }
}
