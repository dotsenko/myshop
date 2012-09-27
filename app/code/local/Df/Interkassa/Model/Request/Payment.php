<?php


class Df_Interkassa_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (

				self::REQUEST_VAR__HTTP_METHOD__CONFIRM => self::REQUEST_VALUE__HTTP_METHOD_POST

				,
				self::REQUEST_VAR__HTTP_METHOD__RETURN_OK => self::REQUEST_VALUE__HTTP_METHOD_POST

				,
				self::REQUEST_VAR__HTTP_METHOD__RETURN_NO => self::REQUEST_VALUE__HTTP_METHOD_POST

				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()

				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()


				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD => Df_Core_Const::T_EMPTY

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
	 * @return Df_Interkassa_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_Interkassa_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_Interkassa_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_Interkassa_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_Interkassa_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_Interkassa_Model_Config_Service);

		return $result;

	}




	const REQUEST_VAR__HTTP_METHOD__CONFIRM = 'ik_status_method';
	const REQUEST_VAR__HTTP_METHOD__RETURN_OK = 'ik_success_method';
	const REQUEST_VAR__HTTP_METHOD__RETURN_NO = 'ik_fail_method';


	const REQUEST_VAR__ORDER_AMOUNT = 'ik_payment_amount';
	const REQUEST_VAR__ORDER_COMMENT = 'ik_payment_desc';
	const REQUEST_VAR__ORDER_NUMBER = 'ik_payment_id';


	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD = 'ik_paysystem_alias';


	const REQUEST_VAR__SHOP_ID = 'ik_shop_id';


	const REQUEST_VAR__URL_CONFIRM = 'ik_status_url';
	const REQUEST_VAR__URL_RETURN_OK = 'ik_success_url';
	const REQUEST_VAR__URL_RETURN_NO = 'ik_fail_url';


	const REQUEST_VALUE__HTTP_METHOD_POST = 'POST';
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Interkassa_Model_Request_Payment';
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


