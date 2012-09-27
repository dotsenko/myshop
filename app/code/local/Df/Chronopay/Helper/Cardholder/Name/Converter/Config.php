<?php


class Df_Chronopay_Helper_Cardholder_Name_Converter_Config extends Mage_Core_Helper_Abstract {


	/**
	 * @return array
	 */
	public function getConversionTable () {
		return
			array (
				"Æ" => "AE"
				, "Ø" => "OE"
				, "Å" => "AA"
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Helper_Cardholder_Name_Converter_Config';
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