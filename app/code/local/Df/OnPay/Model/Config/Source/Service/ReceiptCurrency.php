<?php

class Df_OnPay_Model_Config_Source_Service_ReceiptCurrency extends Df_Payment_Model_Config_Source {


	/**
	 * @override
	 * @param bool $isMultiSelect
	 * @return array
	 */
	protected function toOptionArrayInternal ($isMultiSelect = false) {

		/** @var array $result  */
		$result =
			array (
				array (
					self::OPTION_KEY__VALUE => self::VALUE__PAYMENT
					,
					self::OPTION_KEY__LABEL =>
						df_helper()->payment()->__ ('в валюте платежа')
				)
				,
				array (
					self::OPTION_KEY__VALUE => self::VALUE__BILL
					,
					self::OPTION_KEY__LABEL =>
						df_helper()->payment()->__ ('в валюте счёта')
				)
			)
		;


		df_result_array ($result);

		return $result;

	}


	const VALUE__PAYMENT = 'в валюте платежа';
	const VALUE__BILL = 'в валюте счёта';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_OnPay_Model_Config_Source_Service_ReceiptCurrency';
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


