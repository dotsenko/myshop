<?php


class Df_RbkMoney_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (

				self::REQUEST_VAR__CUSTOMER__EMAIL =>
					$this->getOrder()->getCustomerEmail()

				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_CURRENCY =>
					$this->getServiceConfig()->getCurrencyCodeInServiceFormat()
				,

				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()
				,


				self::REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE =>
					$this->getServiceConfig()->getLocaleCodeInServiceFormat()
				,


				self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

				,
				self::REQUEST_VAR__URL_RETURN_OK =>	$this->getUrlCheckoutSuccess()
				,
				self::REQUEST_VAR__URL_RETURN_NO =>	$this->getUrlCheckoutFail()
				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PROTOCOL_VERSION => 2
			)
		;


		if (!is_null ($this->getServiceConfig()->getSelectedPaymentMethod())) {

			$result [self::REQUEST_VAR__SPECIFIC_PAYMENT_METHOD] =
				$this->getServiceConfig()->getSelectedPaymentMethod()
			;

		}
	
	
		df_result_array ($result);
	
		return $result;
	
	}
	





	/**
	 * @override
	 * @return Df_RbkMoney_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_RbkMoney_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_RbkMoney_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_RbkMoney_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_RbkMoney_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_RbkMoney_Model_Config_Service);

		return $result;

	}






	const REQUEST_VAR__CUSTOMER__EMAIL = 'user_email';

	const REQUEST_VAR__ORDER_AMOUNT = 'recipientAmount';
	const REQUEST_VAR__ORDER_COMMENT = 'serviceName';
	const REQUEST_VAR__ORDER_CURRENCY = 'recipientCurrency';
	const REQUEST_VAR__ORDER_NUMBER = 'orderId';


	const REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE = 'language';

	const REQUEST_VAR__PAYMENT_SERVICE__PROTOCOL_VERSION = 'version';


	const REQUEST_VAR__SHOP_ID = 'eshopId';
	const REQUEST_VAR__SPECIFIC_PAYMENT_METHOD = 'preference';

	const REQUEST_VAR__URL_RETURN_OK = 'successUrl';
	const REQUEST_VAR__URL_RETURN_NO = 'failUrl';

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RbkMoney_Model_Request_Payment';
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


