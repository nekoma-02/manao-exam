<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Main\ORM\Query\Query;
use Local\Entity\ArtemTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class NewsComp extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }

        if (!isset($arParams["NEWS_IBLOCK_ID"])) {
            $arParams["NEWS_IBLOCK_ID"] = 0;
        }

        if (!isset($arParams["PROPERTY_UF"])) {
            $arParams["PROPERTY_UF"] = trim($arParams["PROPERTY_UF"]);
        }

        return $arParams;
    }


    public function executeComponent()
    {
        $this->checkModule();
        $this->getResult();
        global $APPLICATION;
        $this->setResultCacheKeys(array("COUNT"));
        $APPLICATION->SetTitle(GetMessage("COUNT") . $this->arResult["COUNT"]);
        $this->includeComponentTemplate();
    }

    public function checkModule()
    {
        if (!Loader::includeModule("iblock")) {
            ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
            return;
        }
    }



    private function getUserGroup($userId) {
        global $USER;
            $userGroup = Cuser::GetList(
                ($by = "id"),
                ($order = "asc"),
                array("ID" => $userId),
                array("SELECT" => array($this->arParams["PROPERTY_UF"]))
            )->Fetch()[$this->arParams["PROPERTY_UF"]];

            return $userGroup;
    }

    public function getResult()
    {
        global $USER;
        if ($USER->IsAuthorized()) {

            $userId = $USER->GetID();
            $userGroup = $this->getUserGroup($userId);

            if ($this->StartResultCache(false, array($userGroup, $userId))) {

                $rsUsers = CUser::GetList(
                    ($by = "id"),
                    ($order = "asc"),
                    array(
                        $this->arParams["PROPERTY_UF"] => $userGroup,
                    ),
                    array(
                        "SELECT" => array("LOGIN", "ID")
                    )
                );

                while ($arUser = $rsUsers->GetNext()) {
                    $userList[$arUser["ID"]] = array("LOGIN" => $arUser["LOGIN"], "ID" => $arUser["ID"]);
                    $userListId[] = $arUser["ID"];
                }

                $authorNews = array();
                $news = array();
                
                $rsElements = CIBlockElement::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => $this->arParams["NEWS_IBLOCK_ID"],
                        "PROPERTY_" . $this->arParams["PROPERTY"] => $userListId,
                    ),
                    false,
                    false,
                    array(
                        "NAME",
                        "ACTIVE_FROM",
                        "ID",
                        "IBLOCK_ID",
                        "PROPERTY_" . $this->arParams["PROPERTY"]
                    )
                );
      
                while ($el = $rsElements->GetNext()) {

                    $authorNews[$el["ID"]][] = $el["PROPERTY_" . $this->arParams["PROPERTY"] . "_VALUE"];

                    if (empty($news[$el["ID"]])) {
                        $news[$el["ID"]] = $el;
                    }

                    if ($el["PROPERTY_" . $this->arParams["PROPERTY"] . "_VALUE"] != $userId) {
                        $news[$el["ID"]]["AUTHORS"][] = $el["PROPERTY_" . $this->arParams["PROPERTY"] . "_VALUE"];
                    }
                }

                $count = 0;
                foreach ($news as $key => $value) {

                    if (in_array($userId, $authorNews[$value["ID"]]))
                        continue;

                    foreach ($value["AUTHORS"] as $el) {

                        $userList[$el]["NEWS"][] = $value;
                        $count++;
                    }

                }

                unset($userList[$userId]);


                //echo '<pre>'; print_r($userList); echo '</pre>';

                $result = array();

                // удаляем пользователя из списка пользователей если у него нету записей
                foreach ($userList as $key => $value) {
                    if (!key_exists("NEWS", $value)) {
                        unset($userList[$key]);
                    }
                    //$result[] = $element;
                }

                //echo '<pre>'; print_r($result); echo '</pre>';



                $this->arResult["AUTHORS"] = $userList;
                $this->arResult["COUNT"] = $count;


                // echo '<pre>';
                // print_r($this->arResult);
                // echo '</pre>';
                 //echo '<pre>'; print_r($userList); echo '</pre>';
            } else {
                $this->abortResultCache();
            }
        }
    }
}
