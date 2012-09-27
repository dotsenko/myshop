<?php

/**
 * Товарищ!
 * После изменения структуры БД не забывай удалить кеш, потому что Magento кеширует структуру БД!
 */

/** @var $this Df_1C_Model_Resource_Setup */


$this->startSetup();

/**
 * Область действия внешнего идентификатора 1C сменена с витрины на глобальную
 * (при витринной области действия модуль будет работать некорректно с неосновными витринами)
 */
$this->upgrade_1_0_1();

$this->endSetup();


