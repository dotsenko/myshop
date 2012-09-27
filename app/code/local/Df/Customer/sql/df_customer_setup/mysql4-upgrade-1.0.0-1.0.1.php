<?php

/**
 * Товарищ!
 * После изменения структуры БД не забывай удалить кеш, потому что Magento кеширует структуру БД!
 */

/** @var Df_Customer_Model_Resource_Setup $this */

$this->startSetup();



/* @var Mage_Customer_Helper_Address $addressHelper */
$addressHelper = Mage::helper('customer/address');


/** @var Mage_Core_Model_Store $store */
$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);


/* @var Mage_Eav_Model_Config $eavConfig */
$eavConfig = Mage::getSingleton('eav/config');



// update customer address user defined attributes data
$attributes = array (

	/**
	 * Обратите внимание, что идентификатор характеристики
	 * должен содержать не более 30 символов
	 */
	'df_account_number' => array (
		'label' => 'Номер р/c'
		,
		'frontend_label' => 'Номер р/c'
		,
		'type' => 'varchar'
		,
		'backend_type' => 'varchar'
		,
		'input' => 'text'
		,
		'frontend_input' => 'text'
		,
		'is_user_defined' => 1
		,
		'system' => false
		,
		'visible' => true
		,
		'required' => false
		,
		'multiline_count' => 0
		,
		'validate_rules' => array (
			'max_text_length'   => 255
			,
			'min_text_length'   => 1
		)
		,
		'sort_order' => 140
	)
);


foreach ($attributes as $attributeCode => $data) {

	/** @var string $attributeCode */
	/** @var array $data */

	/** @var Mage_Eav_Model_Entity_Attribute_Abstract $attribute */
    $attribute = $eavConfig->getAttribute('customer_address', $attributeCode);

    $attribute->setData('website', $store->getWebsite());

    $attribute->addData($data);


    $attribute
		->setData(
			'used_in_forms'
			,
			array (
				'adminhtml_customer_address'
				,
				'customer_address_edit'
				,
				'customer_register_address'
        	)
		)
	;

    $attribute->save();
}


$this->run ("

	ALTER TABLE {$this->getTable('sales_flat_quote_address')}
		ADD COLUMN `df_account_number`
			VARCHAR(255)
			CHARACTER SET utf8
			DEFAULT NULL
	;

");



/**
 * Таблица sales_flat_order_address отсутствует в Magento 1.4.0.1
 * (и, может быть, в некоторых чуть более поздних версиях)
 */

/** @var Varien_Db_Adapter_Pdo_Mysql $connection  */
$connection = $this->getConnection();

/** @var bool $tableExists  */
$tableExists = false;

try {
	/** @var array $tableDescription  */
	$tableDescription = $connection->describeTable ($this->getTable('sales_flat_order_address'));
	$tableExists = !empty ($tableDescription);
}
catch (Exception $e){

}


if ($tableExists) {

	$this->run ("

		ALTER TABLE {$this->getTable('sales_flat_order_address')}
			ADD COLUMN `df_account_number`
				VARCHAR(255)
				CHARACTER SET utf8
				DEFAULT NULL
		;

	");

}





$this->endSetup();



