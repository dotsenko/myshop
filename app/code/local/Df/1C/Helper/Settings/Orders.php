<?php

class Df_1C_Helper_Settings_Orders extends Df_Core_Helper_Settings {


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Settings_Orders';
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