<?php


class Df_WebPay_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 * @throws Mage_Core_Exception
	 */
	protected function checkPaymentAmount () {

		df_assert (
				$this->getRequestValuePaymentAmount()->getAsInteger()
			===
				$this->getPaymentAmountFromOrder()->getAsInteger()
			,
			sprintf (
				$this->getMessage (self::CONFIG_KEY__MESSAGE__INVALID__PAYMENT_AMOUNT)
				,
				$this->getPaymentAmountFromOrder()->getAsInteger()
				,
				$this->getServiceConfig()->getCurrencyCode()
				,
				$this->getRequestValuePaymentAmount()->getAsInteger()
				,
				$this->getServiceConfig()->getCurrencyCode()
			)
		);

		return $this;

	}




	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'site_order_id';

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
		$result = $e->getMessage();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getResponseTextForSuccess () {

		/** @var string $result  */
		$result = 'OK';

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getSignatureFromOwnCalculations () {

		/** @var array $signatureParams  */
		$signatureParams =
			array (
				$this->getRequestValueServicePaymentDate()
				,
				$this->getRequestValuePaymentCurrencyCode()
				,
				$this->getRequestValuePaymentAmount()->getAsInteger()
				,
				$this->getRequest()->getParam ('payment_method')
				,
				$this->getRequest()->getParam ('order_id')
				,
				$this->getRequestValueOrderIncrementId()
				,
				$this->getRequestValueServicePaymentId()
				,
				$this->getRequestValueServicePaymentState ()
				,
				$this->getRequest()->getParam ('rrn')
				,
				$this->getResponsePassword()
			)
		;


		df_assert_array ($signatureParams);


		/** @var string $result  */
		$result =
			md5 (
				implode (
					Df_Core_Const::T_EMPTY
					,
					$signatureParams
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
					self::PAYMENT_STATE__AUTHORIZED
					,
					self::PAYMENT_STATE__COMPLETED
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
	protected function processException (Exception $e) {

		parent::processException ($e);

		$this->getResponse ()
			->setHttpResponseCode (
				500
			)
		;

		return $this;

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
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 */
	protected function processResponseForSuccess () {

		parent::processResponseForSuccess();

		$this->getResponse()->setRawHeader ('HTTP/1.0 200 OK');

		return $this;

	}




	/**
	 * @param int $code
	 * @return string
	 */
	private function getPaymentStateMessage ($code) {

		df_param_integer ($code, 0);

		/** @var string $result  */
		$result =
			df_a (
				array (
					self::PAYMENT_STATE__COMPLETED => 'completed'
					,self::PAYMENT_STATE__DECLINED => 'declined'
					,self::PAYMENT_STATE__PENDING => 'pending'
					,self::PAYMENT_STATE__AUTHORIZED => 'authorized'
					,self::PAYMENT_STATE__REFUNDED => 'refunded'
					,self::PAYMENT_STATE__SYSTEM => 'system'
					,self::PAYMENT_STATE__VOIDED => 'voided'
				)
				,
				$code
			)
		;


		df_result_string ($result);

		return $result;

	}





	const PAYMENT_STATE__COMPLETED = 1;
	const PAYMENT_STATE__DECLINED = 2;
	CONST PAYMENT_STATE__PENDING = 3;
	CONST PAYMENT_STATE__AUTHORIZED = 4;
	CONST PAYMENT_STATE__REFUNDED = 5;
	CONST PAYMENT_STATE__SYSTEM = 6;
	CONST PAYMENT_STATE__VOIDED = 7;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebPay_Model_Action_Confirm';
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


