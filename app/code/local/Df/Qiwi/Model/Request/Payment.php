<?php


class Df_Qiwi_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (

				self::REQUEST_VAR__CUSTOMER__PHONE => $this->getQiwiCustomerPhone()

				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_CURRENCY =>
					$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

				,
				self::REQUEST_VAR__ORDER_LIFETIME => 24 * 45


				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()
				,


				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()
				,


				self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()


				,
				self::REQUEST_VAR__CUSTOMER__REGISTERED_ONLY => 0

			)
		;
	
		df_result_array ($result);
	
		return $result;
	
	}






	/**
	 * @override
	 * @return Df_Qiwi_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_Qiwi_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_Qiwi_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_Qiwi_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_Qiwi_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_Qiwi_Model_Config_Service);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getQiwiCustomerPhone () {

		/** @var string $result  */
		$result = $this->getPaymentMethod()->getQiwiCustomerPhone();

		df_assert (10 === strlen ($result));

		df_result_string ($result);

		return $result;

	}




	const REQUEST_VAR__CUSTOMER__PHONE = 'to';

	const REQUEST_VAR__ORDER_AMOUNT = 'summ';
	const REQUEST_VAR__ORDER_COMMENT = 'com';
	const REQUEST_VAR__ORDER_CURRENCY = 'currency';
	const REQUEST_VAR__ORDER_LIFETIME = 'lifetime';
	const REQUEST_VAR__ORDER_NUMBER = 'txn_id';


	const REQUEST_VAR__CUSTOMER__REGISTERED_ONLY = 'check_agt';


	const REQUEST_VAR__SHOP_ID = 'from';


	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Qiwi_Model_Request_Payment';
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


