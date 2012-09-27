<?php


class Df_WalletOne_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	public function getParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getParams()
				,
				array (
					self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()
				)
			)
		;

		df_result_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	public function getTransactionDescription () {

		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_EMPTY
				,
				array (
					'BASE64:'
					,
					base64_encode (
						parent::getTransactionDescription()
					)
				)
			)
		;

		df_result_string ($result);

		return $result;
	}




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
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_ACTION =>
					intval (
							Df_WalletOne_Model_Config_Source_PaymentAction::VALUE__CAPTURE
						===
							$this->getServiceConfig()->getPaymentAction()
					)

				,
				self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

				,
				self::REQUEST_VAR__URL_RETURN_OK =>	$this->getUrlCheckoutSuccess()

				,
				self::REQUEST_VAR__URL_RETURN_NO =>	$this->getUrlCheckoutFail()

				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__ENABLED =>
					df_model (
						Df_WalletOne_Model_Form_Processor_AddPaymentMethods::getNameInMagentoFormat()
						,
						array (
							Df_WalletOne_Model_Form_Processor_AddPaymentMethods::PARAM__FIELD_NAME =>
								self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__ENABLED
							,
							Df_WalletOne_Model_Form_Processor_AddPaymentMethods::PARAM__FIELD_VALUES =>
								$this->getServiceConfig()->getSelectedPaymentMethods()
						)
					)

				,
				self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__DISABLED =>
					df_model (
						Df_WalletOne_Model_Form_Processor_AddPaymentMethods::getNameInMagentoFormat()
						,
						array (
							Df_WalletOne_Model_Form_Processor_AddPaymentMethods::PARAM__FIELD_NAME =>
								self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__DISABLED
							,
							Df_WalletOne_Model_Form_Processor_AddPaymentMethods::PARAM__FIELD_VALUES =>
								$this->getServiceConfig()->getDisabledPaymentMethods()
						)
					)
			)

		;
	
		df_result_array ($result);

		return $result;
	
	}










	/**
	 * @override
	 * @return Df_WalletOne_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_WalletOne_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_WalletOne_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_WalletOne_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_WalletOne_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_WalletOne_Model_Config_Service);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getParamsForSignature () {

		/** @var array $result  */
		$result =
			array_merge (
				$this->preprocessParams (
					array_merge (
						$this->getParamsInternal()
						,
						array(
							self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__ENABLED =>
								$this->getServiceConfig()->getSelectedPaymentMethods()
							,
							self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__DISABLED =>
								$this->getServiceConfig()->getDisabledPaymentMethods()
						)
					)
				)
				,
				array (
					'form_key' => df_mage()->core()->session()->getFormKey()
				)
			)
		;

		df_result_array ($result);

		return $result;
	}



	
	/**
	 * @return string
	 */
	private function getSignature () {

		/** @var string $result  */
		$result = $this->getSignatureGenerator()->getSignature();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Df_WalletOne_Model_Request_SignatureGenerator
	 */
	private function getSignatureGenerator () {
	
		if (!isset ($this->_signatureGenerator)) {
	
			/** @var Df_WalletOne_Model_Request_SignatureGenerator $result  */
			$result = 
				df_model (
					Df_WalletOne_Model_Request_SignatureGenerator::getNameInMagentoFormat()
					,
					array (
						Df_WalletOne_Model_Request_SignatureGenerator::PARAM__ENCRYPTION_KEY =>
							$this->getServiceConfig()->getResponsePassword()
						,
						Df_WalletOne_Model_Request_SignatureGenerator::PARAM__SIGNATURE_PARAMS =>
							$this->getParamsForSignature()

					)
				)
			;
	
	
			df_assert ($result instanceof Df_WalletOne_Model_Request_SignatureGenerator);
	
			$this->_signatureGenerator = $result;
	
		}
	
	
		df_assert ($this->_signatureGenerator instanceof Df_WalletOne_Model_Request_SignatureGenerator);
	
		return $this->_signatureGenerator;
	
	}
	
	
	/**
	* @var Df_WalletOne_Model_Request_SignatureGenerator
	*/
	private $_signatureGenerator;



	


	const REQUEST_VAR__ORDER_AMOUNT = 'WMI_PAYMENT_AMOUNT';
	const REQUEST_VAR__ORDER_COMMENT = 'WMI_DESCRIPTION';
	const REQUEST_VAR__ORDER_CURRENCY = 'WMI_CURRENCY_ID';
	const REQUEST_VAR__ORDER_NUMBER = 'WMI_PAYMENT_NO';


	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_ACTION = 'WMI_AUTO_ACCEPT';
	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__ENABLED = 'WMI_PTENABLED';
	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHOD__DISABLED = 'WMI_PTDISABLED';


	const REQUEST_VAR__SIGNATURE = 'WMI_SIGNATURE';
	const REQUEST_VAR__SHOP_ID = 'WMI_MERCHANT_ID';

	const REQUEST_VAR__URL_RETURN_OK = 'WMI_SUCCESS_URL';
	const REQUEST_VAR__URL_RETURN_NO = 'WMI_FAIL_URL';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Request_Payment';
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


