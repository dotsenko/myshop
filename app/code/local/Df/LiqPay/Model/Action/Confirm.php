<?php


class Df_LiqPay_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {




	/**
	 * @override
	 * @return Zend_Controller_Request_Abstract
	 */
	protected function getRequest () {

		if (!isset ($this->_request)) {

			/** @var Zend_Controller_Request_Abstract $result  */
			$result =
				new Zend_Controller_Request_Http ()
			;


			$result
				->setParams (
					array_merge (
						parent::getRequest()->getParams()

						,

						$this->getPaymentInfoAsArray ()
					)
				)
			;



			df_assert ($result instanceof Zend_Controller_Request_Abstract);

			$this->_request = $result;

		}


		df_assert ($this->_request instanceof Zend_Controller_Request_Abstract);

		return $this->_request;

	}


	/**
	* @var Zend_Controller_Request_Abstract
	*/
	private $_request;





	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'order_id';

		df_result_string ($result);

		return $result;
	}





	/**
	 * @override
	 * @param Exception $e
	 * @return string
	 */
	protected function getResponseTextForError (Exception $e) {

		/** @var string $result  */
		$result = self::RESPONSE_TEXT__FAIL;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getResponseTextForSuccess () {

		/** @var string $result  */
		$result = self::RESPONSE_TEXT__SUCCESS;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getSignatureFromOwnCalculations () {

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
							$this->getResponseXml ()
							,
							$this->getServiceConfig()->getResponsePassword()
						)
					)
					,
					1
				)
			)
		;


		df_assert_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return bool
	 */
	protected function isItPreliminaryNotification () {

		/** @var bool $result  */
		$result =
			!in_array (
				$this->getRequestValueServicePaymentState ()
				,
				array (
					self::PAYMENT_STATE__SUCCESS
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 */
	protected function processPreliminaryNotification () {

		parent::processPreliminaryNotification ();

		$this->getOrder()
			->addStatusHistoryComment(
				$this->getPaymentStateMessage (
					$this->getRequestValueServicePaymentState ()
				)
			)
		;

		$this->getOrder()
			->setData (
				Df_Sales_Const::ORDER_PARAM__IS_CUSTOMER_NOTIFIED
				,
				false
			)
		;

		$this->getOrder()->save();


		return $this;

	}




	/**
	 * @param string $code
	 * @return string
	 */
	private function getPaymentStateMessage ($code) {

		df_param_string ($code, 0);

		/** @var string $result  */
		$result =
			df_a (
				array (
					self::PAYMENT_STATE__DELAYED => 'Покупатель решил платить наличными через терминал Приватбанка'
					,
					self::PAYMENT_STATE__SUCCESS => 'Оплата получена'
					,
					self::PAYMENT_STATE__FAILURE => 'Покупатель отказался от оплаты'
					,
					self::PAYMENT_STATE__WAIT_SECURE =>
					'Покупатель оплатил заказ картой, однако система LiqPay ещё проверяет данный платёж.'
				)
				,
				$code
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getPaymentInfoAsArray () {

		if (!isset ($this->_paymentInfoAsArray)) {

			/** @var array $result  */
			$result = $this->getPaymentInfoAsVarienXml()->asCanonicalArray();

			df_assert_array ($result);

			$this->_paymentInfoAsArray = $result;

		}


		df_result_array ($this->_paymentInfoAsArray);

		return $this->_paymentInfoAsArray;

	}


	/**
	* @var array
	*/
	private $_paymentInfoAsArray;






	/**
	 * @return Varien_Simplexml_Element
	 */
	private function getPaymentInfoAsVarienXml () {

		if (!isset ($this->_paymentInfoAsVarienXml)) {

			/** @var Varien_Simplexml_Element $result  */
			$result =
				new Varien_Simplexml_Element (
					$this->getResponseXml ()
				)
			;


			df_assert ($result instanceof Varien_Simplexml_Element);

			$this->_paymentInfoAsVarienXml = $result;

		}


		df_assert ($this->_paymentInfoAsVarienXml instanceof Varien_Simplexml_Element);

		return $this->_paymentInfoAsVarienXml;

	}


	/**
	* @var Varien_Simplexml_Element
	*/
	private $_paymentInfoAsVarienXml;
	

	
	
	/**
	 * @return string
	 */
	private function getResponseXml () {
	
		if (!isset ($this->_responseXml)) {
	
			/** @var string $result  */
			$result = 
				base64_decode (
					df_request ('operation_xml')
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_responseXml = $result;
	
		}
	
	
		df_result_string ($this->_responseXml);
	
		return $this->_responseXml;
	
	}
	
	
	/**
	* @var string
	*/
	private $_responseXml;	





	const PAYMENT_STATE__SUCCESS = 'success';
	const PAYMENT_STATE__FAILURE = 'failure';
	const PAYMENT_STATE__WAIT_SECURE = 'wait_secure';
	const PAYMENT_STATE__DELAYED = 'delayed';





	const RESPONSE_TEXT__SUCCESS = 'YES';
	const RESPONSE_TEXT__FAIL = 'NO';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_LiqPay_Model_Action_Confirm';
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


