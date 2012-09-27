<?php


class Df_Tweaks_Helper_Settings_Newsletter extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Tweaks_Helper_Settings_Newsletter_Subscription
	 */
	public function subscription () {

		/** @var Df_Tweaks_Helper_Settings_Newsletter_Subscription $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Newsletter_Subscription::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Newsletter_Subscription);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Newsletter';
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