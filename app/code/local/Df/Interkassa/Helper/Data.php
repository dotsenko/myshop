<?php


class Df_Interkassa_Helper_Data extends Mage_Core_Helper_Data {




	const SIGNATURE_PARTS_SEPARATOR = '&';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Interkassa_Helper_Data';
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


