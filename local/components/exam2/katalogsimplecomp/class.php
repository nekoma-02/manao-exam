<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Main\ORM\Query\Query;
use Local\Entity\ArtemTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class KatalogComp extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }

        if (!isset($arParams["PROPERTY_CODE"])) {
            $arParams["PROPERTY_CODE"] = 0;
        }

        if (!isset($arParams["CLASSIF_IBLOCK_ID"])) {
            $arParams["CLASSIF_IBLOCK_ID"] = 0;
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



    private function getFirms()
    {
        $arFirma = array();
        $arFirmaId = array();

        //$this->arResult['COUNT'] = 0;

        $obFirma = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => $this->arParams["CLASSIF_IBLOCK_ID"],
                "CHECK_PERMISSION" => $this->arParams["CACHE_GROUPS"],
                "ACTIVE" => "Y",
            ),
            false,
            false,
            array(
                "NAME",
                "IBLOCK_ID",
                "ID",
            ),
        );

        while ($element = $obFirma->Fetch()) {
            $arFirmaId[] = $element["ID"];
            $arFirma[$element["ID"]] = $element;
        }

        $this->arResult['COUNT'] = count($arFirmaId);
        

        $return['FIRMA'] = $arFirma;
        $return['FIRMA_ID'] = $arFirmaId;

        return $return;
    }


    private function getProductsWithFirma(array $arFirmaId, array &$arFirma)
    {
        $arProduct = array();
        $arProductId = array();

        

        $obProduct = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => $this->arParams["PRODUCTS_IBLOCK_ID"],
                "CHECK_PERMISSION" => $this->arParams["CACHE_GROUPS"],
                "PROPERTY_" . $this->arParams["PROPERTY_CODE"] => $arFirmaId,
                "ACTIVE" => "Y",
            ),
            false,
            false,
            array(
                "NAME",
                "IBLOCK_ID",
                "ID",
                "IBLOCK_SECTION_ID",
                "DETAIL_PAGE_URL",
            ),
        );

        while ($element = $obProduct->GetNextElement()) {
            $arField = $element->GetFields();
            $arField['PROPERTY'] = $element->GetProperties();

            foreach ($arField["PROPERTY"]["FIRMA"]["VALUE"] as $value) {
                $arFirma[$value]["ELEMENTS"][$arField["ID"]] = $arField;
            }

        }

        $return['PRODUCTS_RETURN'] = $arFirma;

        return $return;
    }

    public function getResult()
    {
        global $USER;
        if ($this->startResultCache(false, array($USER->GetGroups()))) {

            $temp = $this->getFirms();
            $arFirma = $temp['FIRMA'];
            $arFirmaId = $temp['FIRMA_ID'];
            unset($temp);

            $temp = $this->getProductsWithFirma($arFirmaId, $arFirma);

            $this->arResult['SECT_ELEMENT'] = $temp['PRODUCTS_RETURN'];

        } else {
            $this->abortResultCache();
        }
    }
}
