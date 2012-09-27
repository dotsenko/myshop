<?php

class Df_IPay_Model_Action_GetPaymentAmount extends Df_IPay_Model_Action_Abstract {



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
	<RequestType>ServiceInfo</RequestType>
	<DateTime>20090124153456</DateTime>
	<PersonalAccount>2</PersonalAccount>
	<Currency>974</Currency>
	<RequestId>9221</RequestId>
</ServiceProvider_Request>
			")
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return Df_IPay_Model_Action_GetPaymentAmount
	 */
	protected function processInternal () {

		$this->getResponseAsSimpleXmlElement()
			->appendChild (
				Df_Varien_Simplexml_Element::createNode ('ServiceInfo')
					->importArray (
						array (
							'Name' =>
								array (
									'Surname' => $this->getOrder()->getCustomerLastname()
									,
									'FirstName' => $this->getOrder()->getCustomerFirstname()
									,
									'Patronymic' => $this->getOrder()->getCustomerMiddlename()
								)
							,
							'Amount' =>
								array (
									'Debt' => $this->getRequestPayment()->getAmount()->getAsInteger()
								)
							,
							'Address' =>
								array (
									'City' => $this->getRequestPayment()->getBillingAddress()->getCity()
								)
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
		return self::TRANSACTION_STATE__SERVICE_INFO;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Action_GetPaymentAmount';
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


