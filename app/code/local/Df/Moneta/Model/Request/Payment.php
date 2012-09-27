<?php


class Df_Moneta_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (

				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()

				,
				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()

				,
				self::REQUEST_VAR__ORDER_CURRENCY =>
					$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()


				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD =>
					$this->getServiceConfig()->getSelectedPaymentMethodCode()

				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHODS =>
					$this->getPaymentMethodsAllowed()

				,
				self::REQUEST_VAR__REQUEST__TEST_MODE =>
					intval (
						$this->getPaymentMethod()->isTestMode()
					)

				,
				self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()

				,
				self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()


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
	 * @return Df_Moneta_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_Moneta_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_Moneta_Model_Payment);

		return $result;

	}



	/**
	 * @return string
	 */
	private function getPaymentMethodsAllowed () {

		if (!isset ($this->_paymentMethodsAllowed)) {

			/** @var string $result  */
			$result =
				implode (
					Df_Core_Const::T_COMMA
					,
					$this->getServiceConfig()->getSelectedPaymentMethodCodes()
				)
			;


			df_assert_string ($result);

			$this->_paymentMethodsAllowed = $result;

		}


		df_result_string ($this->_paymentMethodsAllowed);

		return $this->_paymentMethodsAllowed;

	}


	/**
	* @var string
	*/
	private $_paymentMethodsAllowed;







	/**
	 * @return Df_Moneta_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_Moneta_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_Moneta_Model_Config_Service);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getSignature () {

		return
			md5 (
				implode (
					Df_Core_Const::T_EMPTY
					,
					$this->preprocessParams (
						array (
							self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

							,
							self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

							,
							self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()

							,
							self::REQUEST_VAR__ORDER_CURRENCY =>
								$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

							,
							self::REQUEST_VAR__REQUEST__TEST_MODE =>
								intval (
									$this->getPaymentMethod()->isTestMode()
								)

							,
							self::SIGNATURE_PARAM__ENCRYPTION_KEY =>
								$this->getServiceConfig()->getResponsePassword()

						)
					)
				)
			)
		;
	}

	const REQUEST_VAR__ORDER_AMOUNT = 'MNT_AMOUNT';
	const REQUEST_VAR__ORDER_COMMENT = 'MNT_DESCRIPTION';
	const REQUEST_VAR__ORDER_CURRENCY = 'MNT_CURRENCY_CODE';
	const REQUEST_VAR__ORDER_NUMBER = 'MNT_TRANSACTION_ID';


	const REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE = 'moneta.locale';


	const REQUEST_VAR__REQUEST__TEST_MODE = 'MNT_TEST_MODE';


	const REQUEST_VAR__SIGNATURE = 'MNT_SIGNATURE';
	const REQUEST_VAR__SHOP_ID = 'MNT_ID';

	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD = 'paymentSystem.unitId';
	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHODS = 'paymentSystem.limitIds';

	const REQUEST_VAR__URL_RETURN_OK = 'MNT_SUCCESS_URL';
	const REQUEST_VAR__URL_RETURN_NO = 'MNT_FAIL_URL';


	const SIGNATURE_PARAM__ENCRYPTION_KEY = 'encryption_key';

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Moneta_Model_Request_Payment';
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


