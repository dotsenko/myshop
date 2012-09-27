<?php


class Df_EasyPay_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {

		/** @var array $result  */
		$result =
			array_merge (
				array (

					self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()


					,
					self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()


					,
					self::REQUEST_VAR__ORDER_AMOUNT =>
						$this
							->getAmount()
							/**
							 * EASYPAY требует, чтобы суммы были целыми числами
							 */
							->getAsInteger()


					,
					'EP_Expires' => 3


					,
					self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()


					,
					'EP_OrderInfo' => $this->getTransactionDescription()


					,
					self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()


					,
					self::REQUEST_VAR__URL_RETURN_OK =>	$this->getUrlCheckoutSuccess()


					,
					self::REQUEST_VAR__URL_RETURN_NO =>	$this->getUrlCheckoutFail()


					,
					'EP_URL_Type' => 'link'


					,
					self::REQUEST_VAR__REQUEST__TEST_MODE =>
						intval (
							$this->getServiceConfig()->isTestMode ()
						)


					,
					'EP_Encoding' => 'utf-8'

				)
			)
		;

		df_result_array ($result);

		return $result;
	
	}








	/**
	 * @override
	 * @return Df_EasyPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_EasyPay_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_EasyPay_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_EasyPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_EasyPay_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_EasyPay_Model_Config_Service);

		return $result;

	}

	


	/**
	 * @return string
	 */
	private function getSignature () {

		/** @var string $result */
		$result =
			md5 (
				implode (
					Df_Core_Const::T_EMPTY
					,
					$this->preprocessParams (
						array (

							self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

							,
							'Encryption Key' =>
								$this->getServiceConfig()->getResponsePassword()

							,
							self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

							,
							self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsInteger()

						)
					)
				)
			)
		;

		df_result_string ($result);

		return $result;
	}
	
	



	const REQUEST_VAR__SHOP_ID = 'EP_MerNo';
	const REQUEST_VAR__ORDER_NUMBER = 'EP_OrderNo';
	const REQUEST_VAR__ORDER_AMOUNT = 'EP_Sum';
	const REQUEST_VAR__ORDER_COMMENT = 'EP_Comment';
	const REQUEST_VAR__SIGNATURE = 'EP_Hash';
	const REQUEST_VAR__URL_RETURN_OK = 'EP_Success_URL';
	const REQUEST_VAR__URL_RETURN_NO = 'EP_Cancel_URL';
	const REQUEST_VAR__REQUEST__TEST_MODE = 'EP_Debug';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_EasyPay_Model_Request_Payment';
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


