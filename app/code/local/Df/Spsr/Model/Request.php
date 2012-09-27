<?php

class Df_Spsr_Model_Request extends Df_Shipping_Model_Request {


	/**
	 * @override
	 * @return string
	 */
	protected function getQueryHost () {
		return 'www.spsr.ru';
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Request';
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
