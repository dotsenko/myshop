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
	->modifyColumn (
		$this->getTable('cms/page')
		,
		'website_root'
		,
		"tinyint(1) NOT NULL default '0'"
	)
;



$this->endSetup();