<?php


class Df_PayOnline_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @override
	 * @return string
	 */
	 public function getTransactionDescription () {

		/** @var string $result  */
		$result =
			preg_replace (
				'#[^a-zA-Zа-яА-Я0-9 \,\!\?\;\:\%\*\(\)-]#u'
				,
				Df_Core_Const::T_EMPTY
				,
				parent::getTransactionDescription()
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
				self::REQUEST_VAR__CUSTOMER__ADDRESS => $this->getAddressStreet()
				,
				self::REQUEST_VAR__CUSTOMER__CITY => $this->getBillingAddress()->getCity()
				,
				self::REQUEST_VAR__CUSTOMER__COUNTRY =>
					$this->getBillingAddress()->getCountryModel()->getIso3Code()
				,
				self::REQUEST_VAR__CUSTOMER__EMAIL => $this->getOrder()->getCustomerEmail()

				,
				self::REQUEST_VAR__CUSTOMER__ZIP => $this->getBillingAddress()->getPostcode()


				,
				self::REQUEST_VAR__ENCODING => 'utf-8'

				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()

				,
				self::REQUEST_VAR__ORDER_CURRENCY =>
					$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

				,
				self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

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
	 * @return Df_PayOnline_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_PayOnline_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_PayOnline_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_PayOnline_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_PayOnline_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_PayOnline_Model_Config_Service);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getSignature () {

		return
			strtolower (
				md5 (
					implode (
						Df_PayOnline_Helper_Data::SIGNATURE_PARTS_SEPARATOR
						,
						df_helper()->payOnline()->preprocessSignatureParams (
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
									self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()


									,
									self::SIGNATURE_PARAM__PRIVATE_SECURITY_KEY =>
										$this->getServiceConfig()->getResponsePassword()

								)
							)
						)
					)
				)
			)
		;
	}



	const REQUEST_VAR__CUSTOMER__ADDRESS = 'Address';
	const REQUEST_VAR__CUSTOMER__CITY = 'City';
	const REQUEST_VAR__CUSTOMER__COUNTRY = 'Country';
	const REQUEST_VAR__CUSTOMER__EMAIL = 'Email';
	const REQUEST_VAR__CUSTOMER__PHONE = 'Phone';
	const REQUEST_VAR__CUSTOMER__ZIP = 'Zip';


	const REQUEST_VAR__ENCODING = 'Encoding';


	const REQUEST_VAR__ORDER_AMOUNT = 'Amount';
	const REQUEST_VAR__ORDER_COMMENT = 'OrderDescription';
	const REQUEST_VAR__ORDER_CURRENCY = 'Currency';
	const REQUEST_VAR__ORDER_NUMBER = 'OrderId';


	const REQUEST_VAR__SIGNATURE = 'SecurityKey';
	const REQUEST_VAR__SHOP_ID = 'MerchantId';

	const REQUEST_VAR__URL_RETURN_OK = 'ReturnUrl';
	const REQUEST_VAR__URL_RETURN_NO = 'FailUrl';


	const SIGNATURE_PARAM__PRIVATE_SECURITY_KEY = 'PrivateSecurityKey';


	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PayOnline_Model_Request_Payment';
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


