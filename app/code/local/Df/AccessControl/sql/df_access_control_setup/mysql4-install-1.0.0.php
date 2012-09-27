<?php

/**
 * Товарищ!
 * После изменения структуры БД не забывай удалить кеш, потому что Magento кеширует структуру БД!
 */

/** @var Mage_Eav_Model_Entity_Setup $this */



$this->startSetup();




/***************************************************************************
 * Создаём таблицу ролей, для которых задействован наш модуль
 */


$tableAccessControlRole =
	$this->getTable (
		Df_AccessControl_Const::DF__TABLE__ACCESS_CONTROL__ROLE
	)
;




$this->run("
DROP TABLE IF EXISTS `{$tableAccessControlRole}`;
");




$this->run("

CREATE TABLE `{$tableAccessControlRole}` (

	`role_id` int(10) unsigned NOT NULL PRIMARY KEY

	,
	CONSTRAINT `FK___DF__ACCESS_CONTROL_ROLE__ROLE`
		FOREIGN KEY (`role_id`)
		REFERENCES `{$this->getTable('admin_role')}` (`role_id`)
		ON DELETE CASCADE


	--  Товарные разделы,
	--  которыми будет ограничен доступ представителей данной роли к товарному каталогу.
	,
	`categories` text


	--  Магазины и витрины,
	--  которыми будет ограничен доступ представителей данной роли.
	,
	`stores` text


) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");



/****************************************************************************/





$this->endSetup();








