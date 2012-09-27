<?php


class Df_Qiwi_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'order_increment_id';

		df_result_string ($result);

		return $result;
	}




	/**
	 * @override
	 * @return Df_Qiwi_Model_Action_Confirm
	 */
	public function process () {

		try {
			$this->getSoapServer()->handle();
		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
		}

		parent::process();

		return $this;

	}




	/**
	 * @param stdClass $params
	 * @return int
	 */
	public function updateBill ($params) {


		$this->getRequest()
			->setParams (
				array (
					$this->getRequestKeyShopId() => df_a ($params, 'login')
					,
					$this->getRequestKeySignature () => df_a ($params, 'password')
					,
					$this->getRequestKeyOrderIncrementId () => df_a ($params, 'txn')
					,
					$this->getRequestKeyServicePaymentState() => intval (df_a ($params, 'status'))
				)
			)
		;

		/** @var string $result  */
		$result = 0;

		df_result_integer ($result);

		return $result;

	}






	/**
	 * @override
	 * @return Df_Qiwi_Model_Action_Confirm
	 * @throws Mage_Core_Exception
	 */
	protected function checkPaymentAmount () {

		return $this;

	}




	/**
	 * @override
	 * @param Exception $e
	 * @return string
	 */
	protected function getResponseTextForError (Exception $e) {

		/** @var string $result  */
		$result = $this->getSoapServer()->getLastResponse();

		df_result_string ($result);

		return $result;

	}
	



	/**
	 * @override
	 * @return string
	 */
	protected function getResponseTextForSuccess () {

		/** @var string $result  */
		$result = $this->getSoapServer()->getLastResponse();

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
			strtoupper (
				md5 (
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							$this->adjustSignatureParamEncoding (
								$this->getRequestValueOrderIncrementId()
							)
							,
							strtoupper (
								md5 (
									$this->adjustSignatureParamEncoding (
										$this->getResponsePassword()
									)
								)
							)
						)
					)
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
				self::PAYMENT_STATE__PROCESSED
			!==
				intval (
					$this->getRequestValueServicePaymentState ()
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
					intval (
						$this->getRequestValueServicePaymentState ()
					)
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
	 * @param string $signatureParam
	 * @return string
	 */
	private function adjustSignatureParamEncoding ($signatureParam) {

		df_param_string ($signatureParam, 0);

		/** @var string $result  */
		$result =
			df_text()->convertUtf8ToWindows1251 ($signatureParam)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param int $code
	 * @return string
	 */
	private function getPaymentStateMessage ($code) {

		df_param_integer ($code, 0);

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if ($code <= self::PAYMENT_STATE__BILL_CREATED) {
			$result = self::T__PAYMENT_STATE__BILL_CREATED;
		}
		else if ($code < self::PAYMENT_STATE__PROCESSED) {
			$result = self::T__PAYMENT_STATE__PROCESSING;
		}
		else if ($code === self::PAYMENT_STATE__PROCESSED) {
			$result = self::T__PAYMENT_STATE__PROCESSED;
		}
		else if ($code >= self::PAYMENT_STATE__CANCELLED) {
			$result = self::T__PAYMENT_STATE__CANCELLED__OTHER;

			if ($code === self::PAYMENT_STATE__CANCELLED__TERMINAL_ERROR) {
				$result = self::T__PAYMENT_STATE__CANCELLED__TERMINAL_ERROR;
			}
			else if ($code === self::PAYMENT_STATE__CANCELLED__AUTH_ERROR) {
				$result = self::T__PAYMENT_STATE__CANCELLED__AUTH_ERROR;
			}
			else if ($code === self::PAYMENT_STATE__CANCELLED__TIMEOUT) {
				$result = self::T__PAYMENT_STATE__CANCELLED__TIMEOUT;
			}
		}


		$result =
			implode (
				Df_Core_Const::T_SPACE
				,
				df_clean (
					array (
						df_helper()->qiwi()->__ ($result)
						,
						sprintf (
							df_helper()->qiwi()->__ ('Код состояния платежа: «%d».')
							,
							$code
						)
					)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Zend_Soap_Server
	 */
	private function getSoapServer () {

		if (!isset ($this->_soapServer)) {

			/** @var Zend_Soap_Server $result  */
			$result =
				new Zend_Soap_Server (
					//'https://ishop.qiwi.ru/docs/IShopClientWS.wsdl'
					Mage::getConfig()->getModuleDir('etc', 'Df_Qiwi') . DS. 'IShopClientWS.wsdl'
					,
					array ('encoding' => 'UTF-8')
				)
			;

			df_assert ($result instanceof Zend_Soap_Server);


			/**
			 * Soap 1.2 и так является значением по умолчанию,
			 * но данным выражением мы явно это подчёркиваем.
			 */
			$result->setSoapVersion (SOAP_1_2);


			$result->setObject ($this);

			$result->setReturnResponse (true);

			$this->_soapServer = $result;

		}


		df_assert ($this->_soapServer instanceof Zend_Soap_Server);

		return $this->_soapServer;

	}


	/**
	* @var Zend_Soap_Server
	*/
	private $_soapServer;






	const PAYMENT_STATE__BILL_CREATED = 50;
	const PAYMENT_STATE__PROCESSING = 52;

	const PAYMENT_STATE__PROCESSED = 60;

	const PAYMENT_STATE__CANCELLED = 100;
	const PAYMENT_STATE__CANCELLED__TERMINAL_ERROR = 150;
	const PAYMENT_STATE__CANCELLED__AUTH_ERROR = 151;
	const PAYMENT_STATE__CANCELLED__OTHER = 160;
	const PAYMENT_STATE__CANCELLED__TIMEOUT = 161;



	const T__PAYMENT_STATE__BILL_CREATED = 'Счёт выставлен.';
	const T__PAYMENT_STATE__PROCESSING = 'Проводится платёж...';

	const T__PAYMENT_STATE__PROCESSED = 'Счёт оплачен.';

	const T__PAYMENT_STATE__CANCELLED__TERMINAL_ERROR = 'Счёт отменён из-за сбоя на терминале.';
	const T__PAYMENT_STATE__CANCELLED__AUTH_ERROR =
		'Счёт отменён. Возможные причины: недостаточно средств на балансе,
		отклонен абонентом при оплате с лицевого счета оператора сотовой связи и т.п.'
	;
	const T__PAYMENT_STATE__CANCELLED__OTHER = 'Счёт отменён.';
	const T__PAYMENT_STATE__CANCELLED__TIMEOUT = 'Счёт отменён, т.к. истекло время его оплаты.';






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Qiwi_Model_Action_Confirm';
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


