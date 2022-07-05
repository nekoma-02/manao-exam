<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<p><b><?= GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE") ?></b></p>
<?php

echo '<a href = ' . '"' . $APPLICATION->GetCurPage() . '?F=Y">' . $APPLICATION->GetCurPage() . '?F=Y' . '</a>';
?>
<br>
Метка времени - <? echo time();?>
<br>
<?
if (count($arResult["NEWS"]) > 0) {
?>
    <?php
    $this->AddEditAction("add_element", $arResult["ADD_URL"], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_ADD"));

    ?>
        <ul id="<?=$this->GetEditAreaId("add_element");?>">

        <?php
        foreach ($arResult["NEWS"] as $new) {
        ?>
            <li>
                <b>
                    <?= $new["NAME"]; ?>
                </b>
                - <?= $new["ACTIVE_FROM"]; ?>
                (<?= implode(",", $new["SECTIONS"]); ?>)

            </li>

            <?php
            if (count($new["PRODUCTS"]) > 0) {
            ?>

                <ul>
                    <?php
                    foreach ($new["PRODUCTS"] as $product) {
                    ?>
                        <?
                        $this->AddEditAction($new["ID"] . "_" . $product['ID'], $product['EDIT_URL'], CIBlock::GetArrayByID($product["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($new["ID"] . "_" . $product['ID'], $product['DELETE_URL'], CIBlock::GetArrayByID($product["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <li id="<?= $this->GetEditAreaId($new["ID"] . "_" . $product['ID']); ?>">

                            <?= $product["NAME"]; ?> -
                            <?= $product["PROPERTY_PRICE_VALUE"]; ?> -
                            <?= $product["PROPERTY_MATERIAL_VALUE"]; ?> -
                            <?= $product["PROPERTY_ARTNUMBER_VALUE"]; ?> -
                            <?= $product["DETAIL_PAGE"]; ?>

                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
    </ul>
                    </br>
                    --- 
                    <p>
                        <b>
                            Навигация
                        </b>
                    </p>
                    <?php echo $arResult["NAV_STRING"]; ?>
<?php } ?>