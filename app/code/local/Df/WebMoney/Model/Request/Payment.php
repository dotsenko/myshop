<?php


class Df_WebMoney_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (

				self::REQUEST_VAR__CUSTOMER__EMAIL => $this->getOrder()->getCustomerEmail()

				,
				self::REQUEST_VAR__HTTP_METHOD__RETURN_OK => self::REQUEST_VALUE__HTTP_METHOD_POST

				,
				self::REQUEST_VAR__HTTP_METHOD__RETURN_NO => self::REQUEST_VALUE__HTTP_METHOD_POST

				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_COMMENT => base64_encode ($this->getTransactionDescription())

				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()
				,

				self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

				,
				self::REQUEST_VAR__URL_CONFIRM => $this->getUrlConfirm()

				,
				self::REQUEST_VAR__URL_RETURN_OK =>	$this->getUrlCheckoutSuccess()

			,
				self::REQUEST_VAR__URL_RETURN_NO =>	$this->getUrlCheckoutFail()

			)
		;
	
		df_result_array ($result);
	
		return $result;
	
	}
	





	/**
	 * @override
	 * @return Df_WebMoney_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_WebMoney_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_WebMoney_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_WebMoney_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_WebMoney_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_WebMoney_Model_Config_Service);

		return $result;

	}






	const REQUEST_VAR__CUSTOMER__EMAIL = 'LMI_PAYMER_EMAIL';


	const REQUEST_VAR__HTTP_METHOD__RETURN_OK = 'LMI_SUCCESS_METHOD';
	const REQUEST_VAR__HTTP_METHOD__RETURN_NO = 'LMI_FAIL_METHOD';


	const REQUEST_VAR__ORDER_AMOUNT = 'LMI_PAYMENT_AMOUNT';
	const REQUEST_VAR__ORDER_COMMENT = 'LMI_PAYMENT_DESC_BASE64';
	const REQUEST_VAR__ORDER_NUMBER = 'LMI_PAYMENT_NO';


	const REQUEST_VAR__SHOP_ID = 'LMI_PAYEE_PURSE';


	const REQUEST_VAR__URL_CONFIRM = 'LMI_RESULT_URL';
	const REQUEST_VAR__URL_RETURN_OK = 'LMI_SUCCESS_URL';
	const REQUEST_VAR__URL_RETURN_NO = 'LMI_FAIL_URL';


	const REQUEST_VALUE__HTTP_METHOD_POST = 'POST';
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebMoney_Model_Request_Payment';
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


