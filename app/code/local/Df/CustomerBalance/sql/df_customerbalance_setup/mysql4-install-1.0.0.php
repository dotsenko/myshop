<?php


/** @var Df_CustomerBalance_Model_Mysql4_Setup $this */
$this->startSetup();


/** @var Varien_Db_Adapter_Pdo_Mysql $connection  */
$connection = $this->getConnection();


$tableBalance = $this->getTable('df_customerbalance/balance');
$tableHistory = $this->getTable('df_customerbalance/balance_history');


$this->run("

	CREATE TABLE `{$tableBalance}` (
	  `balance_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `customer_id` int(10) unsigned NOT NULL DEFAULT 0,
	  `website_id` smallint(5) unsigned NOT NULL DEFAULT 0,
	  `amount` decimal(12,4) NOT NULL DEFAULT 0,
	  PRIMARY KEY (`balance_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `{$tableHistory}` (
	  `history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `balance_id` int(10) unsigned NOT NULL DEFAULT 0,
	  `updated_at` datetime NULL DEFAULT NULL,
	  `action` tinyint(3) unsigned NOT NULL default '0',
	  `balance_amount` decimal(12,4) unsigned NOT NULL DEFAULT 0,
	  `balance_delta` decimal(12,4) NOT NULL DEFAULT 0,
	  `additional_info` tinytext COLLATE utf8_general_ci NULL,
	  `is_customer_notified` tinyint(1) unsigned NOT NULL DEFAULT 0,
	  PRIMARY KEY (`history_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;")
;



$connection
	->addConstraint (
		'FK_CUSTOMERBALANCE_CUSTOMER', $tableBalance, 'customer_id'
		, $this->getTable('customer/entity'), 'entity_id'
	)
;


$connection
	->addKey(
		$tableBalance, 'UNQ_CUSTOMERBALANCE_CW'
		, array('customer_id', 'website_id'), 'unique'
	)
;


$connection
	->addConstraint(
		'FK_CUSTOMERBALANCE_HISTORY_BALANCE', $tableHistory, 'balance_id'
		, $tableBalance, 'balance_id'
	)
;




$this->addAttribute('quote', 'customer_balance_amount_used', array('type'=>'decimal'));
$this->addAttribute('quote', 'base_customer_balance_amount_used', array('type'=>'decimal'));

$this->addAttribute('quote_address', 'base_customer_balance_amount', array('type'=>'decimal'));
$this->addAttribute('quote_address', 'customer_balance_amount', array('type'=>'decimal'));

$this->addAttribute('order', 'base_customer_balance_amount', array('type'=>'decimal'));
$this->addAttribute('order', 'customer_balance_amount', array('type'=>'decimal'));

$this->addAttribute('order', 'base_customer_balance_invoiced', array('type'=>'decimal'));
$this->addAttribute('order', 'customer_balance_invoiced', array('type'=>'decimal'));

$this->addAttribute('order', 'base_customer_balance_refunded', array('type'=>'decimal'));
$this->addAttribute('order', 'customer_balance_refunded', array('type'=>'decimal'));

$this->addAttribute('invoice', 'base_customer_balance_amount', array('type'=>'decimal'));
$this->addAttribute('invoice', 'customer_balance_amount', array('type'=>'decimal'));

$this->addAttribute('creditmemo', 'base_customer_balance_amount', array('type'=>'decimal'));
$this->addAttribute('creditmemo', 'customer_balance_amount', array('type'=>'decimal'));


$this->addAttribute('quote', 'use_customer_balance', array('type'=>'int'));


$connection
	->changeColumn(
		$tableBalance, 'website_id', 'website_id', 'smallint(5) unsigned NULL DEFAULT NULL'
	)
;


$connection
	->addConstraint(
		'FK_CUSTOMERBALANCE_WEBSITE', $tableBalance, 'website_id'
		,
		$this->getTable('core/website'), 'website_id', 'SET NULL'
	)
;



$this->run("
    DELETE FROM {$tableBalance} WHERE website_id IS NULL;
");


$connection->dropForeignKey($tableBalance, 'FK_CUSTOMERBALANCE_WEBSITE');
$connection->dropKey($tableBalance, 'FK_CUSTOMERBALANCE_WEBSITE');

$connection
	->changeColumn(
		$tableBalance, 'website_id', 'website_id'
		,
		'smallint(5) unsigned NOT NULL DEFAULT 0'
	)
;

$connection
	->addConstraint(
		'FK_CUSTOMERBALANCE_WEBSITE', $tableBalance, 'website_id'
		,
		$this->getTable('core/website'), 'website_id'
	)
;



$this
	->addAttribute(
		'creditmemo', 'base_customer_balance_total_refunded', array('type'=>'decimal')
	)
;


$this
	->addAttribute(
		'creditmemo', 'customer_balance_total_refunded', array('type'=>'decimal')
	)
;


$this
	->addAttribute(
		'order', 'base_customer_balance_total_refunded', array('type'=>'decimal')
	)
;


$this->addAttribute('order', 'customer_balance_total_refunded', array('type'=>'decimal'));



$connection
	->addColumn (
		$tableBalance, 'base_currency_code', 'CHAR( 3 ) NULL DEFAULT NULL'
	)
;


$connection
	->changeColumn(
		$tableBalance
		,
		'website_id', 'website_id', 'SMALLINT(5) UNSIGNED NULL DEFAULT NULL'
	)
;


$connection
	->addConstraint(
		'FK_CUSTOMERBALANCE_WEBSITE', $tableBalance, 'website_id'
		,
		$this->getTable('core/website'), 'website_id', 'SET NULL'
	)
;





$this->endSetup();
