<?php

class Df_WalletOne_Model_Config_Source_PaymentAction extends Df_Payment_Model_Config_Source {


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
					self::OPTION_KEY__VALUE => self::VALUE__AUTHORIZE
					,
					self::OPTION_KEY__LABEL =>
						df_helper()->walletOne ()->__ ('зачислять платежи после моего ручного подтверждения')
				)
				,
				array (
					self::OPTION_KEY__VALUE => self::VALUE__CAPTURE
					,
					self::OPTION_KEY__LABEL =>
						df_helper()->walletOne ()->__ ('зачислять платежи автоматически')
				)
			)
		;


		df_result_array ($result);

		return $result;

	}


	const VALUE__AUTHORIZE = 'authorize';
	const VALUE__CAPTURE = 'capture';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Config_Source_PaymentAction';
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


