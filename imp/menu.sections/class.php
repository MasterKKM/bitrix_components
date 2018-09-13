<?php
/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global CIntranetToolbar $INTRANET_TOOLBAR
 */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Highloadblock\HighloadBlockTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class MenuSectionComponent extends CBitrixComponent
{
    private $resMap = array();

    /**
     * Нежное тельце компанента.
     * @return array|bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        if (!CModule::IncludeModule("highloadblock")) {
            ShowError('Модуль highloadblock не установлен.');
            return false;
        }
        $hlBlock = HighloadBlockTable::getById($this->arParams['HL_BLOCK_ID'])->fetch();
        if (empty($hlBlock)) {
            ShowError('500', 'Не известный Id блока данных.');
            return false;
        }
        $classInstance = HighloadBlockTable::compileEntity($hlBlock)->getDataClass();

        // Получаем все категории.
        $tab = $classInstance::getList()->fetchAll();

        // Подготавливаем список категорий.
        do {
            $flag = false;
            foreach ($tab as $i => $item) {
                if (empty($item[$this->arParams['HL_BLOCK_PARENT_NAME']])) {
                    // Вставляем записи верхнего уровня.
                    $item[$this->arParams['HL_BLOCK_PARENT_NAME']] = 0;
                    $item['DEPTH_LEVEL'] = 1;
                    $this->resMap[] = $item;
                    // Удаляем перенесенный элемент.
                    unset($tab[$i]);
                    continue;
                }
                // Вставляем записи после родительского элемента.
                if ($this->insertInMap($item[$this->arParams['HL_BLOCK_PARENT_NAME']], $item)) {
                    // Удаляем перенесенный элемент.
                    unset($tab[$i]);
                    $flag = true;
                }
            }
        } while ($flag);

        // Собираем меню.
        $rez = array();
        foreach ($this->resMap as $item) {
            $line = str_replace('#ID#', $item['ID'], $this->arParams['HL_BLOCK_SECTION_URL']);
            $rez[] = array(
                $item[$this->arParams['HL_BLOCK_SECTION_NAME']],
                $line,
                array(
                    $line,
                ),
                array(
                    'FROM_IBLOCK' => true,
                    'IS_PARENT' => $item['IS_PARENT'],
                    'DEPTH_LEVEL' => $item['DEPTH_LEVEL'],
                ),
            );
        }

        return $rez;
    }

    /**
     * Найти элемент в таблице записей.
     * @param $niddle string/integer Id искомой записи.
     * @return bool|int
     */
    public function findInMap($niddle)
    {
        for ($i = 0; $i < count($this->resMap); $i++) {
            if ($this->resMap[$i]['ID'] == $niddle) {
                return $i;
            }
        }
        return false;
    }

    /**
     * Вставить запись после данной.
     * @param $id integer - Id данной записи.
     * @param $item array - Вставляемая запись.
     * @return bool
     */
    public function insertInMap($id, $item)
    {
        $pos = $this->findInMap($id);
        if ($pos !== false) {
            $this->resMap[$pos]['IS_PARENT'] = true;
            $item['DEPTH_LEVEL'] = $this->resMap[$pos]['DEPTH_LEVEL'] + 1;
            array_splice($this->resMap, $pos + 1, 0, array($item));
            return true;
        } else {
            return false;
        }
    }
}
