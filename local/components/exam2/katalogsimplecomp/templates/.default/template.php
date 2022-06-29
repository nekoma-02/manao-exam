<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<p><b><?= GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE") ?></b></p>
<?php
if (count($arResult["SECT_ELEMENT"]) > 0) {
?>
    <ul>
        <?php
        foreach ($arResult["SECT_ELEMENT"] as $product) {
        ?>
            <li>
                <b>
                    <?= $product["NAME"]; ?>
                </b>
                <?php if (count($product["ELEMENTS"]) > 0) { ?>
                    <ul>
                        <?php
                        foreach ($product["ELEMENTS"] as $element) {
                        ?>
                            <li>

                                <a href="<?= $element['DETAIL_PAGE_URL'] ?>"> <?= $element["NAME"]; ?></a> -
                                <?= $element["PROPERTY"]["PRICE"]["VALUE"]; ?> -
                                <?= $element["PROPERTY"]["MATERIAL"]["VALUE"]; ?> -
                                <?= $element["PROPERTY"]["ARTNUMBER"]["VALUE"]; ?>

                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>

            </li>


        <?php } ?>
    </ul>
<?php } ?>