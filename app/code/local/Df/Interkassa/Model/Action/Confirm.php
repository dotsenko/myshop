<?php


class Df_Interkassa_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {


	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'ik_payment_id';

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

		/** @var array $signatureParams  */
		$signatureParams =
			array (
				$this->getRequestValueShopId()
				,
				$this->getRequestValuePaymentAmountAsString()
				,
				$this->getRequestValueOrderIncrementId()
				,
				$this->getRequest()->getParam ('ik_paysystem_alias')
				,
				$this->getRequest()->getParam ('ik_baggage_fields')
				,
				$this->getRequestValueServicePaymentState()
				,
				$this->getRequestValueServicePaymentId()
				,
				$this->getRequest()->getParam ('ik_currency_exch')
				,
				$this->getRequest()->getParam ('ik_fees_payer')
				,
				$this->getResponsePassword()
			)
		;


		df_assert_array ($signatureParams);


		/** @var string $result  */
		$result =
			strtoupper (
				md5 (
					implode (
						self::SIGNATURE_PARTS_SEPARATOR
						,
						$signatureParams
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
				/**
				 * Дело в том, что, как я понял,
				 * в случае оплаты покупателем заказа электронной валютой,
				 * платёжная система сразу отсылает статус «paid»,
				 * а в случае оплаты банковской картой —
				 * сначала «authorized», и лишь потом — «paid».
				 */
				!$this->getOrder()->canInvoice()
			||
				!in_array (
					$this->getRequestValueServicePaymentState ()
					,
					array (
						self::PAYMENT_STATE__PAID
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
					self::PAYMENT_STATE__PAID => 'Оплата получена'
					,
					self::PAYMENT_STATE__CANCELED => 'Покупатель отказался от оплаты'
				)
				,
				$code
			)
		;


		df_result_string ($result);

		return $result;

	}




	const PAYMENT_STATE__PAID = 'success';
	const PAYMENT_STATE__CANCELED = 'fail';


	const RESPONSE_TEXT__SUCCESS = 'YES';
	const RESPONSE_TEXT__FAIL = 'NO';


	const SIGNATURE_PARTS_SEPARATOR = ':';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Interkassa_Model_Action_Confirm';
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


