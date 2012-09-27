<?php


class Df_Catalog_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Catalog_Helper_Settings_Navigation
	 */
	public function navigation () {
		return Mage::helper (Df_Catalog_Helper_Settings_Navigation::getNameInMagentoFormat());
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Settings';
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


}