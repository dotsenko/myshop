<?php

class Df_Garantpost_Model_Request_Rate_Heavy_Ground extends Df_Garantpost_Model_Request_Rate_Heavy {


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Rate_Heavy_Ground';
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


