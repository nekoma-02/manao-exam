<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$prices = [];

foreach ($arResult["NEWS"] as $news) {
    foreach ($news["PRODUCTS"] as $product) {
        //print_r($product);
        if (!empty($product["PROPERTY_PRICE_VALUE"])) {
            $prices[] = $product["PROPERTY_PRICE_VALUE"];
        }
    }
}

$arResult["MIN_PRICE"] = min($prices);
$arResult["MAX_PRICE"] = max($prices);

$this->__component->SetResultCacheKeys(array("MIN_PRICE","MAX_PRICE"));


