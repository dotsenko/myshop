<?php


/* @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();


$tableInvitation        = $installer->getTable('df_invitation/invitation');
$tableInvitationHistory = $installer->getTable('df_invitation/invitation_history');
$tableInvitationTrack = $installer->getTable('df_invitation/invitation_track');

$tableCustomer      = $installer->getTable('customer/entity');
$tableCustomerGroup = $installer->getTable('customer/customer_group');



$installer->run("
DROP TABLE IF EXISTS `{$tableInvitation}`;
CREATE TABLE `{$tableInvitation}` (
    `invitation_id` INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `customer_id` INT( 10 ) UNSIGNED DEFAULT NULL ,
    `date` DATETIME NOT NULL ,
    `email` VARCHAR( 255 ) NOT NULL ,
    `referral_id` INT( 10 ) UNSIGNED DEFAULT NULL ,
    `protection_code` CHAR(32) NOT NULL,
    `signup_date` DATETIME DEFAULT NULL,
    `store_id` SMALLINT(5) UNSIGNED NOT NULL,
    `group_id` smallint(3) unsigned NULL DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `status` enum('new','sent','accepted','canceled') NOT NULL DEFAULT 'new',
    INDEX `IDX_customer_id` (`customer_id`),
    INDEX `IDX_referral_id` (`referral_id`)

    ,
  	CONSTRAINT `FK_INVITATION_STORE`
		FOREIGN KEY (`store_id`)
		REFERENCES `{$installer->getTable('core_store')}` (`store_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE


    ,
  	CONSTRAINT `FK_INVITATION_CUSTOMER`
		FOREIGN KEY (`customer_id`)
		REFERENCES `{$tableCustomer}` (`entity_id`)
		ON DELETE SET NULL
		ON UPDATE CASCADE


    ,
  	CONSTRAINT `FK_INVITATION_REFERRAL`
		FOREIGN KEY (`referral_id`)
		REFERENCES `{$tableCustomer}` (`entity_id`)
		ON DELETE SET NULL
		ON UPDATE CASCADE



    ,
  	CONSTRAINT `FK_INVITATION_CUSTOMER_GROUP`
		FOREIGN KEY (`group_id`)
		REFERENCES `{$tableCustomerGroup}` (`customer_group_id`)
		ON DELETE SET NULL
		ON UPDATE CASCADE


) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");



/** @var Varien_Db_Adapter_Pdo_Mysql $connection  */
$connection = $installer->getConnection();





$installer->run("
DROP TABLE IF EXISTS `{$tableInvitationHistory}`;
CREATE TABLE `{$tableInvitationHistory}` (
    `history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `invitation_id` INT UNSIGNED NOT NULL,
    `date` DATETIME NOT NULL,
    `status` enum('new','sent','accepted','canceled') NOT NULL DEFAULT 'new',
    INDEX `IDX_invitation_id` (`invitation_id`)

    ,
  	CONSTRAINT `FK_INVITATION_HISTORY_INVITATION`
		FOREIGN KEY (`invitation_id`)
		REFERENCES `{$tableInvitation}` (`invitation_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");





$installer->run("
CREATE TABLE `{$tableInvitationTrack}` (
	`track_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`inviter_id` int(10) unsigned NOT NULL DEFAULT 0,
	`referral_id` int(10) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`track_id`),
	UNIQUE KEY `UNQ_INVITATION_TRACK_IDS` (`inviter_id`,`referral_id`),
	KEY `FK_INVITATION_TRACK_REFERRAL` (`referral_id`),
	CONSTRAINT `FK_INVITATION_TRACK_INVITER`
		FOREIGN KEY (`inviter_id`)
		REFERENCES `{$tableCustomer}` (`entity_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE

	,
	CONSTRAINT `FK_INVITATION_TRACK_REFERRAL`
		FOREIGN KEY (`referral_id`)
		REFERENCES `{$tableCustomer}` (`entity_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");





$installer->endSetup();
