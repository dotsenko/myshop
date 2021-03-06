<?php

class Df_Catalog_Model_Product_Collection extends Df_Varien_Data_Collection {


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Product_Collection';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}











	protected $_itemObjectClass = 'Mage_Catalog_Model_Product';


}

