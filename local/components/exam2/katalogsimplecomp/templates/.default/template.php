<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<p><b><?= GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE") ?></b></p>
<?php
if (count($arResult["NEWS"]) > 0) {
?>
    <ul>
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
                        <li>

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
<?php } ?>