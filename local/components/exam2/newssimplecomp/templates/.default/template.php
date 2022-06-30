<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<p><b><?= GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE") ?></b></p>

<?php if (count($arResult["AUTHORS"]) > 0) { ?>
    <ul>
        <? foreach ($arResult["AUTHORS"] as $key => $arAuthor) { ?>
            <li>
                <b>
                [<?= $key; ?>] <?= $arAuthor["LOGIN"]; ?>
                </b>
                <?php if (count($arAuthor["NEWS"]) > 0) { ?>
                    <ul>
                        <? foreach ($arAuthor["NEWS"] as $arNews) { ?>
                            <li>
                                - <?= $arNews["NAME"]; ?>
                            </li>
                        <? } ?>
                    </ul>
                <?php } ?>
            </li>
        <? } ?>
    </ul>
<?php } ?>