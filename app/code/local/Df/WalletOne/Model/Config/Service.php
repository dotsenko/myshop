<?php

class Df_WalletOne_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {



	/**
	 * @return string
	 */
	public function getPaymentAction () {

		/** @var string $result  */
		$result =
			$this->getVar (
				self::KEY__VAR__PAYMENT_ACTION
			)
		;


		df_result_string ($result);

		return $result;

	}



	const KEY__VAR__PAYMENT_ACTION = 'payment_action';




	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Config_Service';
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


