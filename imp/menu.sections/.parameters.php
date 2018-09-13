<?php
/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * @global CUserTypeManager $USER_FIELD_MANAGER
 */

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('highloadblock');

// Получаем список Hi-Load модулей.
$p = HighloadBlockTable::getList()->fetchAll();
$hlBlocks = array();
foreach ($p as $item) {
    $hlBlocks[$item['ID']] = $item['NAME'];
}

if (empty($arCurrentValues['HL_BLOCK_ID'])) {
    $arCurrentValues['HL_BLOCK_ID'] = key($hlBlocks);
}
global $USER_FIELD_MANAGER;
/** @noinspection PhpMethodOrClassCallIsNotCaseSensitiveInspection */
$uFields = $USER_FIELD_MANAGER->getUserFields('HLBLOCK_' . $arCurrentValues['HL_BLOCK_ID']);
$fields = array();
foreach ($uFields as $field) {
    $fields[$field['FIELD_NAME']] = $field['FIELD_NAME'];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "SETTINGS" => array(
            "NAME" => GetMessage('IMP_SECTION_SETTINGS_NAME'),
        ),
    ),
    "PARAMETERS" => array(
        "HL_BLOCK_ID" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('IMP_SECTION_HL_BLOCK_NAME'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $hlBlocks,
            "REFRESH" => "Y",
        ),
        "HL_BLOCK_PARENT_NAME" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('IMP_SECTION_PARENT_FIELD_NAME'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $fields,
            "REFRESH" => "N",
        ),
        "HL_BLOCK_SECTION_NAME" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('IMP_SECTION_CATEGORY_FIELD_NAME'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $fields,
            "REFRESH" => "N",
        ),
        'HL_BLOCK_SECTION_URL' => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('IMP_SECTION_HL_BLOCK_PATH'),
            "TYPE" => "STRING",
            "VALUES" => '',
            "REFRESH" => "N",
            'DEFAULT' => 'particion/#ID#/',
        ),
    ),
);

