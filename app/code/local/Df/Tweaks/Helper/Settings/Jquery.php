<?php


class Df_Tweaks_Helper_Settings_Jquery extends Df_Core_Helper_Settings_Jquery {


	/**
	 * @override
	 * @return string
	 */
	public function getLoadMode () {
		return
			Mage::getStoreConfig (
				'df_tweaks/other/jquery_load_mode'
			)
		;
	}
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Jquery';
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