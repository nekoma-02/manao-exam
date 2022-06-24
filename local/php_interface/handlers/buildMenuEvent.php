<?php
// файл /bitrix/php_interface/init.php
// регистрируем обработчик


namespace Local\Handlers;
 
IncludeModuleLangFile(__FILE__);
use CEventLog;
use CGroup;

class BuildMenuEvent
{
    static function onBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {

        global $USER;
        $groupUser = $USER::GetUserGroupList($USER->GetID());

        $contentManagerGroupId=CGroup::GetList(
            $by = "c_sort",
            $order = "asc",
            array(
                "STRING_ID"=>"content_editor"
            )
        )->Fetch()["ID"];

        //echo "id контент менеджер ".$contentManagerGroupId;

        $isAdmin = false;
        $isManager = false;

        while ($group = $groupUser->Fetch()) {
            if ($group["GROUP_ID"] == 1) {
                $isAdmin = true;
            }

            if ($group["GROUP_ID"] == $contentManagerGroupId) {
                $isManager = true;
            }
        }

        if ($isManager && !$isAdmin) {

            foreach ($aModuleMenu as $key => $value) {

                if ($value["items_id"] == "menu_iblock_/news") {
                    $aModuleMenu = [$value];

                    foreach ($value["items"] as $child) {

                        if ($child["items_id"] == "menu_iblock_/news/1") {
                            $aModuleMenu[0]['items'] = [$child];
                            break;
                        }

                    }
                    break;

                }
            }
            $aGlobalMenu = ["global_menu_content" => $aGlobalMenu["global_menu_content"]];
        }

        

        // echo '<pre>'; print_r($aGlobalMenu); echo '</pre>';
         //echo '<pre>'; print_r($aModuleMenu); echo '</pre>';
        // echo '<pre>'; print_r($arFields); echo '</pre>';
            
    }
}