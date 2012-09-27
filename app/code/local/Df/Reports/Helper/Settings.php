<?php

class Df_Reports_Helper_Settings extends Df_Core_Helper_Settings {


	
	/**
	 * @return Df_Reports_Helper_Settings_Common
	 */
	public function common () {

		/** @var Df_Reports_Helper_Settings_Common $result  */
		$result =
			Mage::helper (Df_Reports_Helper_Settings_Common::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Reports_Helper_Settings_Common);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Helper_Settings';
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