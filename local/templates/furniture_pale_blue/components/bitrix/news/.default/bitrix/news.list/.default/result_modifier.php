<?php

echo $arParams["SPECIALDATE"];
if ($arParams["SPECIALDATE"] == "Y") {
    $arResult["DATE_FIRST_NEWS"] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
    echo $arResult["DATE_FIRST_NEWS"];
    $this->__component->SetResultCacheKeys(array("DATE_FIRST_NEWS"));
}

