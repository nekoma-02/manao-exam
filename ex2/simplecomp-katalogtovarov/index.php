<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент каталог товаров");
?><?$APPLICATION->IncludeComponent(
	"exam2:katalogsimplecomp", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PRODUCTS_IBLOCK_ID" => "2",
		"NEWS_IBLOCK_ID" => "1",
		"PRODUCTS_IBLOCK_ID_PROPERTY" => "UF_NEWS_LINK",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"DETAIL_URL" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"ELEMENT_IN_PAGE" => "4"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>