<?php

class Df_Checkout_Block_Frontend_Address_AccountNumber
	extends Df_Checkout_Block_Frontend_Address_Element {


	/**
	 * @override
	 * @return string
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;

	}


	const DEFAULT_TEMPLATE = 'df/checkout/address/accountNumber.phtml';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Address_AccountNumber';
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


