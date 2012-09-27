<?php

class Df_Chronopay_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Chronopay_Helper_Cardholder_Name_Converter_Config
	 */
	public function cartholderNameConversionConfig () {

		/** @var Df_Chronopay_Helper_Cardholder_Name_Converter_Config $result  */
		$result =
			Mage::helper (
				Df_Chronopay_Helper_Cardholder_Name_Converter_Config::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Chronopay_Helper_Cardholder_Name_Converter_Config);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Helper_Data';
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
