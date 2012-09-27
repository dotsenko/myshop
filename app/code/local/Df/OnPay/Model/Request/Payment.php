<?php


class Df_OnPay_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



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
				self::REQUEST_VAR__ORDER_AMOUNT =>
					df_helper()->onPay()->priceToString (
						$this->getAmount()
					)

				,

				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()

				,
				self::REQUEST_VAR__ORDER_CURRENCY =>
					$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

				,
				self::REQUEST_VAR__PAYMENT_SERVICE__IS_FEE_PAYED_BY_SHOP =>
						(
								Df_Payment_Model_Config_Source_Service_FeePayer::VALUE__SHOP
							===
								$this->getServiceConfig()->getFeePayer ()
						)
					?
						'true'
					:
						'false'


				,
				self::REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE =>
					$this->getServiceConfig()->getLocaleCodeInServiceFormat()


				,
				self::REQUEST_VAR__PAYMENT_SERVICE__NEED_CONVERT_RECEIPTS =>
					$this->needConvertReceipts()


				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_MODE => self::PAYMENT_MODE__FIX

				,
				self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()

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
	 * @return Df_OnPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_OnPay_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_OnPay_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_OnPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_OnPay_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_OnPay_Model_Config_Service);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getSignature () {

		return
			df_helper()->onPay()->generateSignature (
				$this->preprocessParams (
					array (
						self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_MODE
							=> self::PAYMENT_MODE__FIX

						,
						self::REQUEST_VAR__ORDER_AMOUNT =>
							df_helper()->onPay()->priceToString (
								$this->getAmount()
							)

						,
						self::REQUEST_VAR__ORDER_CURRENCY =>
							$this->getServiceConfig()
								->getCurrencyCodeInServiceFormat()

						,
						self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

						,
						self::REQUEST_VAR__PAYMENT_SERVICE__NEED_CONVERT_RECEIPTS =>
							$this->needConvertReceipts()

						,
						self::SIGNATURE_PARAM__ENCRYPTION_KEY =>
							$this->getServiceConfig()->getResponsePassword()

					)
				)
			)
		;
	}




	/**
	 * @return string
	 */
	private function needConvertReceipts () {

		/** @var string $result  */
		$result =
				(
						Df_OnPay_Model_Config_Source_Service_ReceiptCurrency::VALUE__BILL
					===
						$this->getServiceConfig()->getReceiptCurrency ()
				)
			?
				'yes'
			:
				'no'
		;


		df_result_string ($result);

		return $result;

	}




	const PAYMENT_MODE__FIX = 'fix';



	const REQUEST_VAR__CUSTOMER__EMAIL = 'user_email';


	const REQUEST_VAR__ORDER_AMOUNT = 'price';
	const REQUEST_VAR__ORDER_COMMENT = 'note';
	const REQUEST_VAR__ORDER_CURRENCY = 'currency';
	const REQUEST_VAR__ORDER_NUMBER = 'pay_for';


	const REQUEST_VAR__PAYMENT_SERVICE__IS_FEE_PAYED_BY_SHOP = 'price_final';


	const REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE = 'ln';
	const REQUEST_VAR__PAYMENT_SERVICE__NEED_CONVERT_RECEIPTS = 'convert';


	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_MODE = 'pay_mode';



	const REQUEST_VAR__SIGNATURE = 'md5';

	const REQUEST_VAR__URL_RETURN_OK = 'url_success';
	const REQUEST_VAR__URL_RETURN_NO = 'url_fail';



	const SIGNATURE_PARAM__ENCRYPTION_KEY = 'encryption_key';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_OnPay_Model_Request_Payment';
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


