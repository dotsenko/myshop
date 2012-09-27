<?php

/**
 * После изменения структуры БД надо удалять кеш,
 * потому что Magento кеширует структуру БД!
 */

/* @var Mage_Core_Model_Resource_Setup $this */

$this->startSetup();


/** @var Varien_Db_Adapter_Interface $connection */
$connection = $this->getConnection();



$connection
	/**
	 * Общая колонка для дополнительных настроек рубрики.
	 *
	 * Не выделяем для этих настроек отдельные колонки,
	 * потому что таких настроек может быть много,
	 * и их структура может часто меняться.
	 */
	->addColumn (
		$this->getTable('df_cms/hierarchy_metadata')
		,
		'additional_settings'
		,
		'TEXT DEFAULT NULL'
	)
;


$connection
	->dropColumn (
		$this->getTable('df_cms/hierarchy_metadata')
		,
		'menu_visibility'
	)
;


$connection
	->dropColumn (
		$this->getTable('df_cms/hierarchy_metadata')
		,
		'menu_layout'
	)
;



$this->endSetup();