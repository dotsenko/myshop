<?php


class Df_Tweaks_Helper_Settings_Theme_Modern extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getRemoveBottomBanner () {
		return
			Mage::getStoreConfig (
				'df_tweaks/theme_modern/remove_bottom_banner'
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Theme_Modern';
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