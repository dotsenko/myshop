<?php


/**
 * Resource setup - add columns to roles table:
 * is_all_permissions - yes/no flag
 * website_ids - comma-separated
 * store_group_ids - comma-separated
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();


$tableLog = $installer->getTable('df_logging/event');
$tableLogChanges = $installer->getTable('df_logging/event_changes');
$tableUser = $installer->getTable('admin/user');


/** @var Varien_Db_Adapter_Pdo_Mysql $connection  */
$connection = $installer->getConnection();



$installer->run("DROP TABLE IF EXISTS `".$tableLog."`");
$installer->run("CREATE TABLE `".$tableLog."` (
    `log_id` int(11) NOT NULL auto_increment,
    `ip` bigint(20) NOT NULL default '0',
    `x_forwarded_ip` bigint(20) unsigned NOT NULL default '0',
    `event_code` varchar(100) NOT NULL default '',
    `time` datetime NOT NULL default '0000-00-00 00:00:00',
    `action` char(20) NOT NULL default '-',
    `info` varchar(255) NOT NULL default '-',
    `status` char(15) NOT NULL default 'success',
    `user` varchar(40) NOT NULL default '',
	`user_id` mediumint(9) unsigned NULL DEFAULT NULL,
    `fullaction` varchar(200) NOT NULL default '-',
    `error_message` text DEFAULT NULL,
    PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");




$connection
	->addConstraint(
		'FK_LOGGING_EVENT_USER'
		,
		$tableLog
		,
		'user_id'
		,
		$tableUser
		,
		'user_id'
		,
		'SET NULL'
	)
;



$connection
	->addKey (
		$tableLog
		,
		'IDX_LOGGING_EVENT_USERNAME'
		,
		'user'
	)
;



$installer->run("DROP TABLE IF EXISTS `{$tableLogChanges}`");
$installer->run("CREATE TABLE `".$tableLogChanges."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `source_id` INT( 11 ) NULL DEFAULT NULL,
  `original_data` text NOT NULL,
  `result_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `FK_LOGGING_EVENT_CHANGES_EVENT_ID`
  	FOREIGN KEY (`event_id`) REFERENCES `{$tableLog}` (`log_id`)
  	ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");




$installer->endSetup();
