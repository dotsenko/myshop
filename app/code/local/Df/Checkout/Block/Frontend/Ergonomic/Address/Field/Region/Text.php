<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field {








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text';
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


