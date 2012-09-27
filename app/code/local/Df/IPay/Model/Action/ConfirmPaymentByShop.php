<?php

class Df_IPay_Model_Action_ConfirmPaymentByShop extends Df_IPay_Model_Action_Abstract {



	/**
	 * @override
	 * @return string
	 */
	protected function getRequestAsXml_Test () {

		/** @var string $result  */
		$result =
			df_text()->convertUtf8ToWindows1251 ("<?xml version='1.0' encoding='windows-1251' ?>
<ServiceProvider_Request>
	<Version>1</Version>
	<RequestType>TransactionStart</RequestType>
	<DateTime>20090124153856</DateTime>
	<PersonalAccount>2</PersonalAccount>
	<Currency>974</Currency>
	<RequestId>9221</RequestId>
	<TransactionStart>
		<Amount>1233700</Amount>
		<TransactionId>6180433</TransactionId>
		<Agent>999</Agent>
		<AuthorizationType>iPay</AuthorizationType>
	</TransactionStart>
</ServiceProvider_Request>
			")
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return Df_IPay_Model_Action_ConfirmPaymentByShop
	 */
	protected function processInternal () {

		$this->checkPaymentAmount ();

		$this->getResponseAsSimpleXmlElement()
			->appendChild (
				Df_Varien_Simplexml_Element::createNode ('TransactionStart')
					->importArray (
						array (
							'ServiceProvider_TrxId' => $this->getOrder()->getIncrementId()
							,
							'Info' =>
								array (
									'InfoLine' => $this->getRequestPayment()->getTransactionDescription()
								)
						)
					)
			)
		;

		return $this;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getExpectedRequestType () {
		return self::TRANSACTION_STATE__START;
	}




	/**
	 * @return Df_IPay_Model_Action_ConfirmPaymentByShop
	 * @throws Mage_Core_Exception
	 */
	private function checkPaymentAmount () {

			df_assert (
					$this->getRequestParam_PaymentAmount()->getAsInteger()
				===
					$this->getPaymentAmountFromOrder()->getAsInteger()
				,
				sprintf (
					$this->getMessage (
						Df_Payment_Model_Action_Confirm::CONFIG_KEY__MESSAGE__INVALID__PAYMENT_AMOUNT
					)
					,
					$this->getPaymentAmountFromOrder()->getAsInteger()
					,
					$this->getServiceConfig()->getCurrencyCode()
					,
					$this->getRequestParam_PaymentAmount()->getAsInteger()
					,
					$this->getServiceConfig()->getCurrencyCode()
				)
			)
		;

		return $this;

	}




	/**
	 * @return Df_Core_Model_Money
	 */
	protected function getPaymentAmountFromOrder () {

		if (!isset ($this->_paymentAmountFromOrder)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				$this->getServiceConfig()->getOrderAmountInServiceCurrency (
					$this->getOrder()
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_paymentAmountFromOrder = $result;

		}


		df_assert ($this->_paymentAmountFromOrder instanceof Df_Core_Model_Money);

		return $this->_paymentAmountFromOrder;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_paymentAmountFromOrder;


	



	/**
	 * @return Df_Core_Model_Money
	 */
	protected function getRequestParam_PaymentAmount () {

		if (!isset ($this->_requestParam_paymentAmount)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
							floatval (
								$this->getRequestParam (
									self::REQUEST_PARAM__TRANSACTION_START__AMOUNT
								)
							)
					)
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_requestParam_paymentAmount = $result;

		}


		df_assert ($this->_requestParam_paymentAmount instanceof Df_Core_Model_Money);

		return $this->_requestParam_paymentAmount;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_requestParam_paymentAmount;





	const REQUEST_PARAM__TRANSACTION_START__AMOUNT = 'TransactionStart/Amount';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Action_ConfirmPaymentByShop';
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


