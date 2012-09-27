<?php

class Df_Checkout_Helper_Settings_Patches extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getFixAddress1501 () {
		return
			Mage::getStoreConfig (
				'df_checkout/patches/fix_address_1501'
			)
		;
	}




	/**
	 * @return boolean
	 */
	public function fixSalesConvertOrderToQuote () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/patches/fix_sales_convert_order_to_quote'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Helper_Settings_Patches';
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