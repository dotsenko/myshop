<?php


class Df_Tweaks_Helper_Settings_Banners extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Tweaks_Helper_Settings_Banners_Left
	 */
	public function left () {

		/** @var Df_Tweaks_Helper_Settings_Banners_Left $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Banners_Left::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Banners_Left);

		return $result;

	}


	/**
	 * @return Df_Tweaks_Helper_Settings_Banners_Right
	 */
	public function right () {

		/** @var Df_Tweaks_Helper_Settings_Banners_Right $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Banners_Right::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Banners_Right);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Banners';
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