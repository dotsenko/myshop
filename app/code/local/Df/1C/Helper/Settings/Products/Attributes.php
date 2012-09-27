<?php

class Df_1C_Helper_Settings_Products_Attributes extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function showOnProductPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_1c/products/attributes__show_on_product_page'
				,
				df_helper()->_1c()->cml2()->getStoreProcessed()
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
		return 'Df_1C_Helper_Settings_Products_Attributes';
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