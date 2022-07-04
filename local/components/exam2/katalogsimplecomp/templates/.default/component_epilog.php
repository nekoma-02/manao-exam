<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (isset($arResult["MIN_PRICE"]) && isset($arResult["MAX_PRICE"])) {
    $text = '<div style="color:red; margin: 34px 15px 35px 15px">#MACROS#</div>';
    $includeText = str_replace("#MACROS#", "Минимальная цена: ". $arResult["MIN_PRICE"] ." </br> Масимальная цена: " . $arResult["MAX_PRICE"], $text);
    //echo $includeText;
    $APPLICATION->AddViewContent("price",$includeText);
}

//echo '<pre>'; print_r($arResult); echo '</pre>';