<?php

class Df_Vk_Helper_Data extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Vk_Helper_Settings
	 */
	public function settings () {

		/** @var Df_Vk_Helper_Settings $result  */
		$result =
			Mage::helper (Df_Vk_Helper_Settings::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Helper_Data';
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