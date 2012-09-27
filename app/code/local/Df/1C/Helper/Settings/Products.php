<?php

class Df_1C_Helper_Settings_Products extends Df_Core_Helper_Settings {


	/**
	 * @return Df_1C_Helper_Settings_Products_Attributes
	 */
	public function attributes () {

		/** @var Df_1C_Helper_Settings_Products_Attributes $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Settings_Products_Attributes $result  */
			$result = Mage::helper (Df_1C_Helper_Settings_Products_Attributes::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Settings_Products_Attributes);
		}

		return $result;
	}




	/**
	 * @return string
	 */
	public function getMainPriceName () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_1c/products/main_price_name'
				,
				df_helper()->_1c()->cml2()->getStoreProcessed()
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Settings_Products';
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