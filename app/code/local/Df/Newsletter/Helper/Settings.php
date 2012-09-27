<?php

class Df_Newsletter_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Newsletter_Helper_Settings_Subscription
	 */
	public function subscription () {

		/** @var Df_Newsletter_Helper_Settings_Subscription $result */
		static $result;

		if (!isset ($result)) {

			$result =
				Mage::helper (
					Df_Newsletter_Helper_Settings_Subscription::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Newsletter_Helper_Settings_Subscription);

		}

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Newsletter_Helper_Settings';
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