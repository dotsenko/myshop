<?php


class Df_LiqPay_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


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
	 * @return array
	 */
	protected function getParamsInternal () {
	
		/** @var array $result  */
		$result =
			array (
				'operation_xml' => $this->getXmlEncoded ()
			)
		;
	
		df_result_array ($result);
	
		return $result;
	
	}





	/**
	 * @override
	 * @return Df_LiqPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_LiqPay_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_LiqPay_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_LiqPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_LiqPay_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_LiqPay_Model_Config_Service);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getParamsForXml () {

		if (!isset ($this->_paramsForXml)) {

			/** @var array $result  */
			$result =
				array (
					'version' => '1.2'
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
					self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsString()
					,

					self::REQUEST_VAR__ORDER_COMMENT =>
						/**
						 * LiqPay запрещает кириллицу в запросе
						 */
						df_mage()->catalog()->product()->urlHelper()->format (
							$this->getTransactionDescription()
						)

					,
					self::REQUEST_VAR__ORDER_CURRENCY =>
						$this->getServiceConfig()
							->getCurrencyCodeInServiceFormat()

					,
					self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

					,
					self::REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHODS =>
						implode (
							Df_Core_Const::T_COMMA
							,
							$this->getServiceConfig()->getSelectedPaymentMethods()
						)


					,
					self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

					,
					self::REQUEST_VAR__URL_CONFIRM => $this->getUrlConfirm()

					,
					self::REQUEST_VAR__URL_RETURN =>
						/**
						 * LiqPay, в отличие от других платёжных систем,
						 * не поддерживает разные веб-адреса для успешного и неуспешного сценариев оплаты
						 */
						$this->getUrlReturn()
				)
			;

			df_assert_array ($result);

			$this->_paramsForXml = $result;

		}


		df_result_array ($this->_paramsForXml);

		return $this->_paramsForXml;

	}


	/**
	* @var array
	*/
	private $_paramsForXml;





	/**
	 * @return string
	 */
	private function getSignature () {

		/** @var string $result  */
		$result =
			base64_encode(
				sha1 (
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							$this->getServiceConfig()->getResponsePassword()
							,
							$this->getXml()
							,
							$this->getServiceConfig()->getResponsePassword()
						)
					)
					,
					1
				)
			)
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	private function getUrlReturn () {

		if (!isset ($this->_urlReturn)) {

			/** @var string $result  */
			$result =
				Mage::getUrl (
					implode (
						Df_Core_Const::T_URL_PATH_SEPARATOR
						,
						array (
							$this->getPaymentMethod()->getCode()
							,
							'customerReturn'
						)
					)
					,
					/**
					 * Без _nosid система будет формировать ссылку c ?___SID=U.
					 * На всякий случай избегаем этого.
					 */
					array ('_nosid' => true)
				)
			;


			df_assert_string ($result);

			$this->_urlReturn = $result;

		}


		df_result_string ($this->_urlReturn);

		return $this->_urlReturn;

	}


	/**
	* @var string
	*/
	private $_urlReturn;





	/**
	 * @return string
	 */
	private function getXml () {

		if (!isset ($this->_xml)) {

			/** @var string $result  */
			$result =
				$this->getXmlAsVarienObject ()->toXml (
					/**
					 * Все свойства
					 */
					array ()

					,
					/**
					 * Корневой тэг
					 */
					'request'

					,
					/**
					 * не добавлять <?xml version="1.0" encoding="UTF-8"?>
					 */
					false

					,
					/**
					 * Запрещаем добавление CDATA,
					 * потому что LiqPay эту синтаксическую конструкцию не понимает
					 */
					false
				)
			;


			df_assert_string ($result);

			$this->_xml = $result;

		}


		df_result_string ($this->_xml);

		return $this->_xml;

	}


	/**
	* @var string
	*/
	private $_xml;







	/**
	 * @return Varien_Object
	 */
	private function getXmlAsVarienObject () {

		if (!isset ($this->_xmlAsVarienObject)) {

			/** @var Varien_Object $result  */
			$result =
				new Varien_Object (
					$this->getParamsForXml ()
				)
			;

			df_assert ($result instanceof Varien_Object);

			$this->_xmlAsVarienObject = $result;

		}


		df_assert ($this->_xmlAsVarienObject instanceof Varien_Object);

		return $this->_xmlAsVarienObject;

	}


	/**
	* @var Varien_Object
	*/
	private $_xmlAsVarienObject;





	/**
	 * @return string
	 */
	private function getXmlEncoded () {

		if (!isset ($this->_xmlEncoded)) {

			/** @var string $result  */
			$result =
				base64_encode (
					$this->getXml ()
				)
			;


			df_assert_string ($result);

			$this->_xmlEncoded = $result;

		}


		df_result_string ($this->_xmlEncoded);

		return $this->_xmlEncoded;

	}


	/**
	* @var string
	*/
	private $_xmlEncoded;




	const REQUEST_VAR__CUSTOMER__PHONE = 'default_phone';

	const REQUEST_VAR__ORDER_AMOUNT = 'amount';
	const REQUEST_VAR__ORDER_COMMENT = 'description';
	const REQUEST_VAR__ORDER_CURRENCY = 'currency';
	const REQUEST_VAR__ORDER_NUMBER = 'order_id';

	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_METHODS = 'pay_way';

	const REQUEST_VAR__SIGNATURE = 'signature';

	const REQUEST_VAR__SHOP_ID = 'merchant_id';


	const REQUEST_VAR__URL_CONFIRM = 'server_url';
	const REQUEST_VAR__URL_RETURN = 'result_url';

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_LiqPay_Model_Request_Payment';
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


