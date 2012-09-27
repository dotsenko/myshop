<?php

class Df_Checkout_Helper_Settings_Field extends Df_Core_Helper_Settings {




	/**
	 * @return Df_Checkout_Helper_Settings_Field_Applicability
	 */
	public function applicability () {

		/** @var Df_Checkout_Helper_Settings_Field_Applicability $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Checkout_Helper_Settings_Field_Applicability $result  */
			$result = Mage::helper (Df_Checkout_Helper_Settings_Field_Applicability::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Checkout_Helper_Settings_Field_Applicability);

		}

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Helper_Settings_Field';
	}


	/**
	 * Например, для класса Df_checkoutRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_checkout_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}