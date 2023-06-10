<?php
// НЕ УДАЛЯТЬ!!!
setcookie('PHPSESSID', null, -1, '/', '.ru');

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');
//CModule::IncludeModule('catalog');
//CModule::IncludeModule('sale');
//CModule::IncludeModule('form');

try {
    Loader::registerAutoLoadClasses(null, [
        'Legacy\Config' => '/local/app/classes/config.php',
        'Legacy\Helper' => '/local/app/classes/helper/general.php',

        'Legacy\Api\Auth' => '/local/app/classes/auth/api.php',
        'Legacy\Api\User' => '/local/app/classes/user/api.php',
        'Legacy\Api\Room' => '/local/app/classes/room/api.php',
        'Legacy\Api\Booking' => '/local/app/classes/booking/api.php',
        'Legacy\Api\Company' => '/local/app/classes/company/api.php',
        'Legacy\Api\Deal' => '/local/app/classes/deal/api.php',
        'Legacy\Api\Invoice' => '/local/app/classes/invoice/api.php',
        'Legacy\Api\Act' => '/local/app/classes/act/api.php',
        'Legacy\Api\CRM' => '/local/app/classes/CRM/api.php',
    ]);
} catch (LoaderException $e) {
    // класс не найден :(
    // echo $e;
}

// минифицировать код на выходе
/*AddEventHandler('main', 'OnEndBufferContent', 'ChangeMyContent');
function ChangeMyContent(&$content)
{
    $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
    $replace = array('>', '<', '\\1');
    $content = preg_replace($search, $replace, $content);
}*/

// Свои поля
require $_SERVER['DOCUMENT_ROOT'] . '/local/app/properties/text/general.php';

AddEventHandler('main', 'OnBuildGlobalMenu', 'RemoveMenuItems');
function RemoveMenuItems(&$aGlobalMenu, &$aModuleMenu)
{
    global $USER;
    if ($USER->GetID() != 1) {
        if (!empty($aGlobalMenu['global_menu_marketplace'])) {
            unset($aGlobalMenu['global_menu_marketplace']);
        }

        if (!empty($aModuleMenu)) {
            foreach ($aModuleMenu as $i => $arItem) {
                if ($arItem['parent_menu'] == 'global_menu_marketplace') {
                    unset($aModuleMenu[$i]);
                }
                if ($arItem['items_id'] == 'menu_system') {
                    if ($arItem['parent_menu'] == 'global_menu_settings') {
                        unset($aModuleMenu[$i]);
                    }
                }
            }
        }
    }
}
