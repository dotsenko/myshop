<?php

class Df_Directory_Helper_Settings_Regions extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Directory_Helper_Settings_Regions_Ru
	 */
	public function ru () {

		/** @var Df_Directory_Helper_Settings_Regions_Ru $result  */
		$result =
			Mage::helper (Df_Directory_Helper_Settings_Regions_Ru::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Directory_Helper_Settings_Regions_Ru);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Settings_Regions';
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