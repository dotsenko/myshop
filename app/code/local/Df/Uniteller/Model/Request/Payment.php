<?php


class Df_Uniteller_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (
				self::REQUEST_VAR__CUSTOMER__ADDRESS =>	$this->getAddressStreet()
				,
				self::REQUEST_VAR__CUSTOMER__CITY =>
					$this->getBillingAddress()->getCity()
				,
				self::REQUEST_VAR__CUSTOMER__COUNTRY =>
					$this->getBillingAddress()->getCountryModel()->getName()
				,
				self::REQUEST_VAR__CUSTOMER__EMAIL =>
					$this->getOrder()->getCustomerEmail()

				,
				self::REQUEST_VAR__CUSTOMER__NAME_FIRST =>
					$this->getOrder()->getCustomerFirstname()

				,
				self::REQUEST_VAR__CUSTOMER__NAME_LAST =>
					$this->getOrder()->getCustomerLastname()

				,
				self::REQUEST_VAR__CUSTOMER__NAME_MIDDLE =>
					$this->getOrder()->getCustomerMiddlename()

				,

				self::REQUEST_VAR__CUSTOMER__PHONE =>
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							'+'
							,
							Df_Core_Model_Format_MobilePhoneNumber::fromString (
								$this->getBillingAddress()->getTelephone()
							)->getOnlyDigits()
						)
					)

				,

				self::REQUEST_VAR__CUSTOMER__STATE =>
					$this->getBillingAddress()->getRegionModel()->getCode()

			,
				self::REQUEST_VAR__CUSTOMER__ZIP => $this->getBillingAddress()->getPostcode()


				,
				self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
				,

				self::REQUEST_VAR__ORDER_COMMENT => $this->getTransactionDescription()

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
	 * @return Df_Uniteller_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_Uniteller_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_Uniteller_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_Uniteller_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_Uniteller_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_Uniteller_Model_Config_Service);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getSignature () {

		return
			strtoupper (
				md5 (
					implode (
						Df_Uniteller_Helper_Data::SIGNATURE_PARTS_SEPARATOR
						,
						array_map (
							'md5'
							,
							$this->preprocessParams (
								array (

									self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

									,
									self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

									,
									self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()

									,
									'dummy-1' => Df_Core_Const::T_EMPTY

									,
									'dummy-2' => Df_Core_Const::T_EMPTY

									,
									'dummy-3' => Df_Core_Const::T_EMPTY

									,
									'dummy-4' => Df_Core_Const::T_EMPTY

									,
									'dummy-5' => Df_Core_Const::T_EMPTY

									,
									'dummy-6' => Df_Core_Const::T_EMPTY

									,
									'dummy-7' => Df_Core_Const::T_EMPTY

									,
									'dummy-8' => $this->getServiceConfig()->getResponsePassword()


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

	const REQUEST_VAR__CUSTOMER__NAME_FIRST = 'FirstName';
	const REQUEST_VAR__CUSTOMER__NAME_LAST = 'LastName';
	const REQUEST_VAR__CUSTOMER__NAME_MIDDLE = 'MiddleName';

	const REQUEST_VAR__CUSTOMER__PHONE = 'Phone';
	const REQUEST_VAR__CUSTOMER__STATE = 'State';
	const REQUEST_VAR__CUSTOMER__ZIP = 'Zip';


	const REQUEST_VAR__ENCODING = 'Encoding';


	const REQUEST_VAR__ORDER_AMOUNT = 'Subtotal_P';
	const REQUEST_VAR__ORDER_COMMENT = 'Comment';
	const REQUEST_VAR__ORDER_CURRENCY = 'Currency';
	const REQUEST_VAR__ORDER_NUMBER = 'Order_IDP';


	const REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE = 'Language';
	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_ACTION = 'Preauth';


	const REQUEST_VAR__SIGNATURE = 'Signature';
	const REQUEST_VAR__SHOP_ID = 'Shop_IDP';

	const REQUEST_VAR__URL_RETURN_OK = 'URL_RETURN_OK';
	const REQUEST_VAR__URL_RETURN_NO = 'URL_RETURN_NO';


	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Uniteller_Model_Request_Payment';
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


