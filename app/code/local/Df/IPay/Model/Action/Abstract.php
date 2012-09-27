<?php

abstract class Df_IPay_Model_Action_Abstract extends Df_Payment_Model_Action_Abstract {


	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getExpectedRequestType ();



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getRequestAsXml_Test ();



	/**
	 * @abstract
	 * @return Df_IPay_Model_Action_Abstract
	 */
	abstract protected function processInternal ();



	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	public function process () {

		try {

			$this->checkSignature ();

			$this->checkRequestType ();

			$this->checkProtocolVersion ();

			$this->checkCurrencyId ();

			$this->checkOrderState ();

			$this->checkOrderPaymentMethod ();

			$this->checkTransactionState ();

			$this->getTransactionState()
				->update (
					$this->getRequestParam_RequestType()
				)
			;

			$this->processInternal ();

		}

		catch (Exception $e) {

			try {
				$this->getTransactionState()->restore();
			}
			catch (Exception $e) {
				/**
				 * Дополнительные сбои нас уже не интересуют.
				 */
			}

			$this->processException ($e);

			df_handle_entry_point_exception ($e, false);
		}

		$this->getResponse()
			->setHeader ('Content-Type', 'text/xml')
			->setHeader (self::HEADER__SIGNATURE, $this->getResponseHeader_Signature())
			->setBody (
				$this->getResponseAsXml ()
			)
		;

		return $this;

	}





	/**
	 * @param string $configKey
	 * @return string
	 */
	protected function getMessage ($configKey) {

		df_param_string ($configKey, 0);

		/** @var string $result  */
		$result =
			str_replace (
				'<br/>'
				,
				"\n"
				,
				$this->getConst ($configKey)
			)
		;


		df_result_string ($result);

		return $result;

	}




	
	/**
	 * @override
	 * @return Mage_Sales_Model_Order
	 */
	protected function getOrder () {
	
		if (!isset ($this->_order)) {
	
			/** @var Mage_Sales_Model_Order $result  */
			$result = 
				df_model (
					Df_Sales_Const::ORDER_CLASS_MF
				)
			;


			$result->load ($this->getOrderId());



			if (
					intval ($result->getId()) !== $this->getOrderId()
				||
					(0 === intval ($result->getId()))
			) {
				df_error (
					sprintf (
						'Заказ номер %d не существует. Начните оплату заново с сайта %s'
						,
						$this->getOrderId ()
						,
						Zend_Uri_Http::fromString (
							Mage::app()->getStore()->getBaseUrl(
								Mage_Core_Model_Store::URL_TYPE_WEB
							)
						)->getHost()
					)
				);
			}


	
	
			df_assert ($result instanceof Mage_Sales_Model_Order);
	
			$this->_order = $result;
	
		}
	
	
		df_assert ($this->_order instanceof Mage_Sales_Model_Order);
	
		return $this->_order;
	
	}
	
	
	/**
	* @var Mage_Sales_Model_Order
	*/
	private $_order;




	/**
	 * @return Mage_Sales_Model_Order_Payment
	 */
	protected function getPayment () {

		/** @var Mage_Sales_Model_Order_Payment $result  */
		$result = $this->getOrder()->getPayment();

		df_assert ($result instanceof Mage_Sales_Model_Order_Payment);

		return $result;

	}





	/**
	 * @override
	 * @return Df_IPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_IPay_Model_Payment $result */
		$result = null;

		try {
			$result = parent::getPaymentMethod();
		}
		catch (Exception $e) {
			/**
			 * Сюда мы попадаем, например, когда нам нужно сформировать цифровую подпись
			 * для ответа о несуществующем заказе
			 */
			$result = df_model (Df_IPay_Model_Payment::getNameInMagentoFormat());
		}


		df_assert ($result instanceof Df_IPay_Model_Payment);

		return $result;

	}





	/**
	 * @return Df_IPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_IPay_Model_Config_Service $result  */
		$result = $this->getRmConfig()->service();

		df_assert ($result instanceof Df_IPay_Model_Config_Service);

		return $result;

	}





	/**
	 * @return array
	 */
	protected function getRequestAsCanonicalArray () {

		if (!isset ($this->_requestAsCanonicalArray)) {

			/** @var Varien_Simplexml_Element $requestAsSimpleXmlElement  */
			$requestAsSimpleXmlElement =
				new Varien_Simplexml_Element (
					$this->getRequestAsXml()
				)
			;


			/** @var array $result  */
			$result = $requestAsSimpleXmlElement->asCanonicalArray();


			df_assert_array ($result);

			$this->_requestAsCanonicalArray = $result;

		}


		df_result_array ($this->_requestAsCanonicalArray);

		return $this->_requestAsCanonicalArray;

	}


	/**
	* @var array
	*/
	private $_requestAsCanonicalArray;





	/**
	 * @param string $paramName
	 * @param string|null $defaultValue
	 * @return string
	 */
	protected function getRequestParam ($paramName, $defaultValue = null) {

		df_param_string ($paramName, 0);


		/** @var string|null $result  */
		$result = null;


		$result =
			df_array_query (
				$this->getRequestAsCanonicalArray()
				,
				$paramName
				,
				$defaultValue
			)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}





	/**
	 * @return Df_IPay_Model_Request_Payment
	 */
	protected function getRequestPayment () {

		if (!isset ($this->_requestPayment)) {

			/** @var Df_IPay_Model_Request_Payment $result  */
			$result =
				df_model (
					Df_IPay_Model_Request_Payment::getNameInMagentoFormat()
					,
					array (
						Df_IPay_Model_Request_Payment::PARAM__ORDER => $this->getOrder()
					)
				)
			;


			df_assert ($result instanceof Df_IPay_Model_Request_Payment);

			$this->_requestPayment = $result;

		}


		df_assert ($this->_requestPayment instanceof Df_IPay_Model_Request_Payment);

		return $this->_requestPayment;

	}


	/**
	* @var Df_IPay_Model_Request_Payment
	*/
	private $_requestPayment;






	/**
	 * @return Df_Varien_Simplexml_Element
	 */
	protected function getResponseAsSimpleXmlElement () {

		if (!isset ($this->_responseAsSimpleXmlElement)) {

			/** @var Df_Varien_Simplexml_Element $result  */
			$result =
				new Df_Varien_Simplexml_Element (
					/**
					 * Обратите внимание,
					 * что хотя iPay ожидает документ в кодировке windows-1251,
					 * здесь, в Magento, документ надо создавать именно в кодировке utf-8,
					 * потому что иначе при попытке добавить к документу в кодировке windows-1251
					 * элементы в кодировке utf-8 (а это кодировка файлов программного кода),
					 * SimpleXml вызовет исключительную ситуацию:
					 * "parser error : switching encoding: encoder error"
					 */
					"<?xml version='1.0' encoding='utf-8'?><ServiceProvider_Response></ServiceProvider_Response>"
				)
			;


			df_assert ($result instanceof Df_Varien_Simplexml_Element);

			$this->_responseAsSimpleXmlElement = $result;

		}


		df_assert ($this->_responseAsSimpleXmlElement instanceof Df_Varien_Simplexml_Element);

		return $this->_responseAsSimpleXmlElement;

	}


	/**
	* @var Df_Varien_Simplexml_Element
	*/
	private $_responseAsSimpleXmlElement;







	/**
	 * @return Mage_Core_Model_Store
	 */
	protected function getStore () {

		if (!isset ($this->_store)) {

			/** @var Mage_Core_Model_Store $result  */
			$result =
					(0 === $this->getOrderId())
				?
					Mage::app()->getStore()
				:
					$this->getOrder()->getStore()
			;


			df_assert ($result instanceof Mage_Core_Model_Store);

			$this->_store = $result;

		}


		df_assert ($this->_store instanceof Mage_Core_Model_Store);

		return $this->_store;

	}


	/**
	* @var Mage_Core_Model_Store
	*/
	private $_store;

	
	
	
	/**
	 * @return string
	 */
	protected function getStoreDomain () {
	
		if (!isset ($this->_storeDomain)) {
	
			/** @var string $result  */
			$result = 
				$this->getStoreUri()->getHost()
			;
	
	
			df_assert_string ($result);
	
			$this->_storeDomain = $result;
	
		}
	
	
		df_result_string ($this->_storeDomain);
	
		return $this->_storeDomain;
	
	}
	
	
	/**
	* @var string
	*/
	private $_storeDomain;	
	
	




	/**
	 * @return Zend_Uri_Http
	 */
	protected function getStoreUri () {

		if (!isset ($this->_storeUri)) {

			/** @var Zend_Uri_Http $result  */
			$result =
				Zend_Uri_Http::fromString (
					$this->getStore()->getBaseUrl(
						Mage_Core_Model_Store::URL_TYPE_WEB
					)
				)
			;


			df_assert ($result instanceof Zend_Uri_Http);

			$this->_storeUri = $result;

		}


		df_assert ($this->_storeUri instanceof Zend_Uri_Http);

		return $this->_storeUri;

	}


	/**
	* @var Zend_Uri_Http
	*/
	private $_storeUri;





	/**
	 * @return Df_IPay_Model_TransactionState
	 */
	protected function getTransactionState () {

		if (!isset ($this->_transactionState)) {

			/** @var Df_IPay_Model_TransactionState $result  */
			$result =
				df_model (
					Df_IPay_Model_TransactionState::getNameInMagentoFormat()
					,
					array (
						Df_IPay_Model_TransactionState::PARAM__PAYMENT => $this->getPayment()
					)
				)
			;


			df_assert ($result instanceof Df_IPay_Model_TransactionState);

			$this->_transactionState = $result;

		}


		df_assert ($this->_transactionState instanceof Df_IPay_Model_TransactionState);

		return $this->_transactionState;

	}


	/**
	* @var Df_IPay_Model_TransactionState
	*/
	private $_transactionState;




	/**
	 * @return string
	 */
	private function calculateRequestSignature () {

		if (!isset ($this->_requestSignature)) {

			/** @var string $result  */
			$result =
				strtoupper (
					md5 (
						implode (
							Df_Core_Const::T_EMPTY
							,
							array (
								$this->getRmConfig()->service()->getResponsePassword ()
								,
								$this->preprocessXmlForSignature (
									$this->getRequestAsXmlInWindows1251()
								)
							)
						)
					)
				)
			;


			df_assert_string ($result);

			$this->_requestSignature = $result;

		}


		df_result_string ($this->_requestSignature);

		return $this->_requestSignature;

	}


	/**
	* @var string
	*/
	private $_requestSignature;





	/**
	 * Обратите внимание, что не при всяком запросе
	 * iPay присылает идентификатор магазина
	 *
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkCurrencyId () {

		df_assert (
				self::EXPECTED_CURRENCY_ID
			===
				$this->getRequestParam_CurrencyId()
			,
			sprintf (
				'Модуль iPay запрограммирован для работы с валютой «%d»,
				однако платёжная система iPay пометила запрос непредусмотренной валютой «%d».'
				,
				self::EXPECTED_CURRENCY_ID
				,
				$this->getRequestParam_CurrencyId()
			)
		);

		return $this;

	}





	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkOrderState () {

		if ($this->getOrder()->canUnhold()) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% не предназначен для оплаты,
					потому что он заморожен.'
				)
			;

			$this->throwOrderNotExists ();

		}


		if ($this->getOrder()->isPaymentReview()) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% не предназначен для оплаты,
					потому что находится на модерации оплаты.'
				)
			;

			$this->throwOrderNotExists ();

		}


		if ($this->getOrder()->isCanceled()) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% не предназначен для оплаты, потому что он отменён.'
				)
			;

			$this->throwOrderNotExists ();

		}


		if (Mage_Sales_Model_Order::STATE_COMPLETE === $this->getOrder()->getState()) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% не предназначен для оплаты, потому что он выполнен.'
				)
			;

			$this->throwOrderAlreadyPayed ();

		}


		if (Mage_Sales_Model_Order::STATE_CLOSED === $this->getOrder()->getState()) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% не предназначен для оплаты, потому что он закрыт.'
				)
			;

			$this->throwOrderAlreadyPayed ();

		}


		if (
				false
			===
				$this->getOrder()->getActionFlag (
					Mage_Sales_Model_Order::ACTION_FLAG_INVOICE
				)
		) {

			$this
				->logOrderMessage (
					'Заказ номер %orderId% помечен системой как непредназначенный для оплаты.'
				)
			;

			$this->throwOrderNotExists ();

		}


		$hasQtyYoInvoice = false;

		foreach ($this->getOrder()->getAllItems() as $item) {

			/** @var Mage_Sales_Model_Order_Item $item */

			if (0 < $item->getQtyToInvoice() && !$item->getLockedDoInvoice()) {
				$hasQtyYoInvoice = true;
				break;
			}
		}

		if (!$hasQtyYoInvoice) {

			/** @var Mage_Sales_Model_Order_Payment|bool $payment */
			$payment = $this->getOrder()->getPayment();

			if (false === $payment) {

				$this
					->logOrderMessage (
						'Заказ номер %orderId% не предназначен для оплаты.'
					)
				;

				$this->throwOrderNotExists ();

			}

			else {

				df_assert ($payment instanceof Mage_Sales_Model_Order_Payment);

				/** @var Mage_Payment_Model_Method_Abstract $paymentMethod */
				$paymentMethod = $payment->getMethodInstance();

				df_assert ($paymentMethod instanceof Mage_Payment_Model_Method_Abstract);


				if (!($paymentMethod instanceof Df_IPay_Model_Payment)) {

					$this
						->logOrderMessage (
							'Заказ номер %orderId% не предназначен для оплаты посредством iPay.'
						)
					;

					$this->throwOrderNotExists ();

				}

				else {

					if (floatval (0) === floatval ($this->getOrder()->getBaseGrandTotal())) {

						$this
							->logOrderMessage (
								'Заказ номер %orderId% не предназначен для оплаты, потому что он бесплатен.'
							)
						;

						$this->throwOrderNotExists ();

					}

					else {

						$this->throwOrderAlreadyPayed ();

					}

				}

			}
		}


		return $this;

	}





	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkProtocolVersion () {

		df_assert (
			self::EXPECTED_PROTOCOL_VERSION === $this->getRequestParam_ProtocolVersion()
			,
			sprintf (
				'Модуль iPay запрограммирован для работы с протоколом iPay версии «%s»,
				однако платёжная система iPay прислала запрос, помеченный версией протокола «%s».'
				,
				self::EXPECTED_PROTOCOL_VERSION
				,
				$this->getRequestParam_ProtocolVersion()
			)
		);

		return $this;

	}




	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkOrderPaymentMethod () {

		if (
			!(
					$this->getOrder()->getPayment()->getMethodInstance()
				instanceof
					Df_IPay_Model_Payment
			)
		)  {
			$this->throwOrderNotExists();
		}

		return $this;

	}





	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkRequestType () {

		if ($this->getRequestParam_RequestType () !== $this->getExpectedRequestType())  {
			df_error (
				sprintf (
					'Класс «%s» предназначен для обработки запросов типа «%s»,
					однако платёжная система iPay прислала ему запрос типа «%s».'
					,
					get_class ($this)
					,
					$this->getExpectedRequestType()
					,
					$this->getRequestParam_RequestType ()
				)
			);
		}

		return $this;

	}





	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkSignature () {

		if (!df_is_it_my_local_pc()) {

			if ($this->getRequestParam_Signature() !== $this->calculateRequestSignature()) {

				df_error (
					sprintf (
						"Запрос от iPay подписан неверно.
						\nОжидаемая подпись: «%s».
						\nПолученная подпись: «%s»."
						,
						$this->calculateRequestSignature()
						,
						$this->getRequestParam_Signature()
					)
				);

			}

		}

		return $this;

	}






	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function checkTransactionState () {

		if (
				(self::TRANSACTION_STATE__START === $this->getTransactionState()->get())
			&&
				in_array (
					$this->getRequestParam_RequestType()
					,
					array (
						self::TRANSACTION_STATE__SERVICE_INFO
						,
						self::TRANSACTION_STATE__START
					)
				)
		) {

			df_error (
				sprintf (
					'Заказ номер %d находится в процессе оплаты'
					,
					$this->getOrder()->getId()
				)
			);

		}


		return $this;

	}




	/**
	 * @return string
	 */
	private function generateResponseSignature () {

		/** @var string $result  */
		$result =
			strtoupper (
				md5 (
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							$this->getRmConfig()->service()->getResponsePassword()
							,
							$this->preprocessXmlForSignature (
								df_text()->convertUtf8ToWindows1251 (
									$this->getResponseAsXml ()
								)
							)
						)
					)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	private function getOrderId () {

		/** @var int $result  */
		$result =
			intval (
				$this->getRequestParam (
					self::REQUEST_PARAM__ORDER_ID
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getRequestAsXml () {

		/** @var string $result  */
		$result =
			df_text()->convertWindows1251ToUtf8 (
				$this->getRequestAsXmlInWindows1251 ()
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getRequestAsXmlInWindows1251 () {

		/** @var string $result  */
		$result =
				df_is_it_my_local_pc()
			?
				$this->getRequestAsXml_Test ()
			:
				$this->getController()->getRequest()->getParam ('XML')
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getRequestHeader_Signature () {

		/** @var string $result  */
		$result =
			$this->getController()->getRequest()->getHeader (
				self::HEADER__SIGNATURE
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	private function getRequestParam_CurrencyId () {

		/** @var int $result  */
		$result = intval ($this->getRequestParam (self::REQUEST_PARAM__CURRENCY_ID));

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getRequestParam_Language () {

		/** @var string $result  */
		$result = $this->getRequestParam (self::REQUEST_PARAM__LANGUAGE);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getRequestParam_ProtocolVersion () {

		/** @var string $result  */
		$result = $this->getRequestParam (self::REQUEST_PARAM__PROTOCOL_VERSION);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getRequestParam_RequestType () {

		/** @var string $result  */
		$result = $this->getRequestParam (self::REQUEST_PARAM__TYPE);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	private function getRequestParam_ShopId () {

		/** @var int $result  */
		$result = intval ($this->getRequestParam (self::REQUEST_PARAM__SHOP_ID));

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getRequestParam_Signature () {

		if (!isset ($this->_requestParam_Signature)) {

			/** @var array $signatureHeaderAsArray  */
			$signatureHeaderAsArray =
				array_map (
					'df_trim'
					,
					explode (':', $this->getRequestHeader_Signature ())
				)
			;

			/** @var string $signatureType */
			$signatureType = df_a ($signatureHeaderAsArray, 0);

			df_assert_string ($signatureType);


			df_assert (
				self::SIGNATYPE_TYPE === $signatureType
				,
				sprintf (
					'Модуль ожидает «%s» в качестве типа цифровой подписи,
					однако подпись от iPay имеет тип «%s».'
					,
					self::SIGNATYPE_TYPE
					,
					$signatureType
				)
			);


			/** @var string $result  */
			$result =
				df_a ($signatureHeaderAsArray, 1)
			;

			df_assert_string ($result);

			$this->_requestParam_Signature = $result;

		}


		df_result_string ($this->_requestParam_Signature);

		return $this->_requestParam_Signature;

	}


	/**
	* @var string
	*/
	private $_requestParam_Signature;





	/**
	 * @return int
	 */
	private function getRequestParam_TransactionId () {

		/** @var int $result  */
		$result = intval ($this->getRequestParam (self::REQUEST_PARAM__TRANSACTION_ID));

		df_result_integer ($result);

		return $result;

	}



	
	
	
	/**
	 * @return string
	 */
	private function getResponseAsXml () {
	
		if (!isset ($this->_responseAsXml)) {
	
			/** @var string $result  */
			$result =
				/**
				 * Похоже, нам не надо здесь вызывать df_text()->convertUtf8ToWindows1251,
				 * потому что библиотека SimpleXml перекодирует текст автоматически
				 * в ту кодировку, которая указана в заголовке XML.
				 */
				$this->getResponseAsSimpleXmlElement()->asXml ()
			;


			$result = str_replace ('utf-8', 'windows-1251', $result);

			df_assert_string ($result);
	
			$this->_responseAsXml = $result;
	
		}
	
	
		df_result_string ($this->_responseAsXml);
	
		return $this->_responseAsXml;
	
	}
	
	
	/**
	* @var string
	*/
	private $_responseAsXml;





	/**
	 * @return string
	 */
	private function getResponseHeader_Signature () {

		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_EMPTY
				,
				array (
					self::SIGNATYPE_TYPE
					,
					': '
					,
					$this->generateResponseSignature()
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param string $message
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function logOrderMessage ($message) {

		df_log (
			str_replace (
				'%ordertId%'
				,
				$this->getOrder()->getId()
				,
				$message
			)
		);

		df_bt ();

		return $this;

	}




	/**
	 * @return Df_IPay_Model_Config_Facade
	 */
	private function getRmConfig () {

		if (!isset ($this->_rmConfig)) {

			/** @var Df_IPay_Model_Payment $paymentIPay  */
			$paymentIPay = df_model (Df_IPay_Model_Payment::getNameInMagentoFormat());

			df_assert ($paymentIPay instanceof Df_IPay_Model_Payment);


			/** @var Mage_Core_Model_Store $store  */
			$store = null;

			try {
				$store = $this->getOrder()->getStore();
			}
			catch (Exception $e) {
				$store = Mage::app()->getStore();
			}

			df_assert ($store instanceof Mage_Core_Model_Store);


			/** @var Df_IPay_Model_Config_Facade $result  */
			$result =
				$paymentIPay->getRmConfig(
					$store
				)
			;


			df_assert ($result instanceof Df_IPay_Model_Config_Facade);

			$this->_rmConfig = $result;

		}


		df_assert ($this->_rmConfig instanceof Df_IPay_Model_Config_Facade);

		return $this->_rmConfig;

	}


	/**
	* @var Df_IPay_Model_Config_Facade
	*/
	private $_rmConfig;




	/**
	 * @param string $xml
	 * @return string
	 */
	private function preprocessXmlForSignature ($xml) {

		/** @var string $result  */
		$result =
			str_replace (
				"\r\n"
				,
				"\n"
				,
				trim (
					$xml
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param Exception $e
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function processException (Exception $e) {

		$this->getResponseAsSimpleXmlElement()
			->appendChild (
			    Df_Varien_Simplexml_Element::createNode (
					'Error'
				)
					->appendChild (
						Df_Varien_Simplexml_Element::createNode (
							'ErrorLine'
						)
							->setCData (
								$e->getMessage()
							)
					)

			)
		;

		return $this;

	}




	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function throwOrderAlreadyPayed () {

		df_error (
			sprintf (
				'Заказ номер %d уже оплачен'
				,
				$this->getOrder()->getId()
			)
		);

		return $this;

	}





	/**
	 * @return Df_IPay_Model_Action_Abstract
	 */
	private function throwOrderNotExists () {

		df_error (
			sprintf (
				'Заказ номер %d не существует. Начните оплату заново с сайта %s'
				,
				$this->getOrder()->getId()
				,
				$this->getStoreDomain ()
			)
		);

		return $this;

	}




	const EXPECTED_CURRENCY_ID = 974;
	const EXPECTED_PROTOCOL_VERSION = '1';


	const HEADER__SIGNATURE = 'ServiceProvider-Signature';


	const MESSAGE__ORDER_NOT_FOUND =
		'Платёжная система iPay прислала сообщение «%s»
		относительно несуществующего заказа с идентификатором «%d».'
	;



	const REQUEST_PARAM__CURRENCY_ID = 'Currency';
	const REQUEST_PARAM__LANGUAGE = 'Language';
	const REQUEST_PARAM__ORDER_ID = 'PersonalAccount';
	const REQUEST_PARAM__PROTOCOL_VERSION = 'Version';
	const REQUEST_PARAM__SHOP_ID = 'ServiceNo';
	const REQUEST_PARAM__TRANSACTION_ID = 'RequestId';
	const REQUEST_PARAM__TYPE = 'RequestType';


	const PAYMENT_PARAM__IPAY_TRANSACTION_STATE = 'df_ipay__transaction_state';

	const SIGNATYPE_TYPE = 'SALT+MD5';

	const TRANSACTION_STATE__RESULT = 'TransactionResult';
	const TRANSACTION_STATE__SERVICE_INFO = 'ServiceInfo';
	const TRANSACTION_STATE__START = 'TransactionStart';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Action_Abstract';
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


