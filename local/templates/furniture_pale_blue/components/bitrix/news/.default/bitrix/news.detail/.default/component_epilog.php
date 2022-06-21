<?php 

//echo '<pre>'; print_r($arResult); echo '</pre>';
if (isset($arResult["REF_CANONICAL"])) {
    $APPLICATION->SetPageProperty("canonical",$arResult["REF_CANONICAL"]);

}
?> 