<?php

use Bitrix\Main\Loader,
    Bitrix\Iblock;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class KatalogComp extends CBitrixComponent
{
    private $isFilter = false;

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }

        if (!isset($arParams["PRODUCTS_IBLOCK_ID"])) {
            $arParams["PRODUCTS_IBLOCK_ID"] = 0;
        }

        if (!isset($arParams["NEWS_IBLOCK_ID"])) {
            $arParams["NEWS_IBLOCK_ID"] = 0;
        }

        return $arParams;
    }


    public function executeComponent()
    {
        $this->checkModule();
        $this->addButtonToSubmenu($this->arParams["PRODUCTS_IBLOCK_ID"]);

        $this->getResult();
        global $APPLICATION;
        $APPLICATION->SetTitle(GetMessage("COUNT") . $this->arResult["PRODUCT_CNT"]);
    }

    private function addButtonToSubmenu($blockID)
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $panel = CIBlock::GetPanelButtons($blockID);

            $this->addIncludeAreaIcons(
                array(
                    array(
                        "ID" => "IB_LINK",
                        "TITLE" => "ИБ в админке",
                        "URL" => $panel["submenu"]["element_List"]["ACTION_URL"],
                        "IN_PARAMS_MENU" => true,
                    )
                )
            );
        }
    }

    public function checkModule()
    {
        if (!Loader::includeModule("iblock")) {
            ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
            return;
        }
    }


    private function getNews()
    {
        $arNews = array();
        $arNewsId = array();

        $obNews = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => $this->arParams["NEWS_IBLOCK_ID"],
                "ACTIVE" => "Y",
            ),
            false,
            array(
                "nPageSize" => $this->arParams["ELEMENT_IN_PAGE"],
                "bShowAll" => true
            ),
            array(
                "NAME",
                "ACTIVE_FROM",
                "ID",
            ),
        );

        $this->arResult["NAV_STRING"] = $obNews->GetPageNavString("Страницы");

        while ($element = $obNews->Fetch()) {
            $arNewsId[] = $element["ID"];
            $arNews[$element["ID"]] = $element;
        }
        $result["AR_NEWS"] = $arNews;
        $result["AR_NEWS_ID"] = $arNewsId;

        return $result;
    }

    private function getSections(array $newsId)
    {
        $arSection = array();
        $arSectionId = array();

        $obSection = CIBlockSection::GetList(
            array(),
            array(
                "IBLOCK_ID" => $this->arParams["PRODUCTS_IBLOCK_ID"],
                "ACTIVE" => "Y",
                $this->arParams["PRODUCTS_IBLOCK_ID_PROPERTY"] => $newsId,
            ),
            false,
            array(
                "NAME",
                "IBLOCK_ID",
                "ID",
                $this->arParams["PRODUCTS_IBLOCK_ID_PROPERTY"],
            ),
            false
        );

        while ($catalog = $obSection->Fetch()) {
            $arSectionId[] = $catalog["ID"];
            $arSection[$catalog["ID"]] = $catalog;
        }

        $result["AR_SECTION"] = $arSection;
        $result["AR_SECTION_ID"] = $arSectionId;

        return $result;
    }

    private function getProducts(array $sectionId, array $arSection, array $arNews)
    {

        $arFilter = array(
            "IBLOCK_ID" => $this->arParams["PRODUCTS_IBLOCK_ID"],
            "ACTIVE" => "Y",
            "SECTION_ID" => $sectionId
        );

        if ($this->isFilter) {
            $arFilter[] = array(
                "LOGIC" => "OR",
                array("<=PROPERTY_PRICE" => 1700, "=PROPERTY_MATERIAL" => "Дерево, ткань"),
                array("<PROPERTY_PRICE" => 1500, "=PROPERTY_MATERIAL" => "Металл, пластик"),

            );
            $this->abortResultCache();
        }

        $obProduct = CIBlockElement::GetList(
            array(
                "NAME" => "asc",
                "SORT" => "asc"
            ),
            $arFilter,
            false,
            false,
            array(
                "NAME",
                "IBLOCK_SECTION_ID",
                "ID",
                "CODE",
                "IBLOCK_ID",
                "PROPERTY_ARTNUMBER",
                "PROPERTY_MATERIAL",
                "PROPERTY_PRICE",

            ),
        );

        $this->arResult["PRODUCT_CNT"] = 0;

        while ($arProduct = $obProduct->GetNext()) {

            $panel = CIBlock::GetPanelButtons(
                $this->arParams["PRODUCTS_IBLOCK_ID"],
                $arProduct["ID"],
                array("SECTION_BUTTONS" => false, "SESSID" => false)
            );

            $arProduct["EDIT_URL"] = $panel["edit"]["edit_element"]["ACTION_URL"];
            $arProduct["DELETE_URL"] = $panel["edit"]["delete_element"]["ACTION_URL"];

            $this->arResult["ADD_URL"] = $arProduct["ADD_URL"] = $panel["configure"]["add_element"]["ACTION_URL"];
            $this->arResult["IBLOCK_ID"] = $this->arParams["PRODUCTS_IBLOCK_ID"];

            $arProduct["DETAIL_PAGE"] = str_replace(
                array(
                    "#SECTION_ID#",
                    "#ELEMENT_CODE#"
                ),
                array(
                    $arProduct["IBLOCK_SECTION_ID"],
                    $arProduct["CODE"],
                ),
                $this->arParams["DETAIL_URL"]
            );

            foreach ($arSection[$arProduct["IBLOCK_SECTION_ID"]][$this->arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $newsId) {
                if ($arNews[$newsId]) {
                    $arNews[$newsId]["PRODUCTS"][] = $arProduct;
                    $this->arResult["PRODUCT_CNT"]++;
                }
            }
        }

        $this->setResultCacheKeys(array("PRODUCT_CNT"));

        return $arNews;
    }


    private function getMinAndMaxPrice($arNews)
    {
        $prices = [];

        foreach ($arNews["NEWS"] as $news) {
            foreach ($news["PRODUCTS"] as $product) {
                //print_r($product);
                if (!empty($product["PROPERTY_PRICE_VALUE"])) {
                    $prices[] = $product["PROPERTY_PRICE_VALUE"];
                }
            }
        }

        $this->arResult["MIN_PRICE"] = min($prices);
        $this->arResult["MAX_PRICE"] = max($prices);

        $this->SetResultCacheKeys(array("MIN_PRICE", "MAX_PRICE"));
    }


    public function getResult()
    {
        $qFilter = $this->request->getQuery("F");
        if (isset($qFilter)) {
            $this->isFilter = true;
        }

        global $CACHE_MANAGER;


        $arNavigation = CDBResult::GetNavParams($this->arNavParams);

        if ($this->StartResultCache(false, array($this->isFilter, $arNavigation),"taggedcache")) {
            $CACHE_MANAGER->RegisterTag("iblock_id_3");

            $temp = $this->getNews();

            $arNews = $temp["AR_NEWS"];
            $arNewsId = $temp["AR_NEWS_ID"];

            unset($temp);

            $temp = $this->getSections($arNewsId);
            $arSection = $temp["AR_SECTION"];
            $arSectionId = $temp["AR_SECTION_ID"];

            unset($temp);

            $arNews = $this->getProducts($arSectionId, $arSection, $arNews);

            foreach ($arSection as $value) {

                foreach ($value[$this->arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $item) {
                    if ($arNews[$item]) {
                        $arNews[$item]["SECTIONS"][] = $value["NAME"];
                    }
                }
            }

            $this->arResult["NEWS"] = $arNews;
            $this->getMinAndMaxPrice($this->arResult);

            $this->includeComponentTemplate();
        } else {
            $this->abortResultCache();
        }
    }
}
