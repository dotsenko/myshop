<?php


class Df_OnPay_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 * @throws Mage_Core_Exception
	 */
	protected function checkPaymentAmount () {

		if (
				$this->isItPreliminaryNotification()
			||
				(
						Df_Payment_Model_Config_Source_Service_FeePayer
							::VALUE__BUYER
					===
						$this->getServiceConfig()->getFeePayer ()
				)
		) {
			parent::checkPaymentAmount();
		}
		else {

			/**
			 * Не проверяем, потому что уж слишком хитро всё там с комиссиями.
			 * Нам достаточно проверки при предварительном запросе и поверки подписи.
			 */

		}


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
		$result = 'pay_for';

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
		$result =
			$this->responseObjectToXml (
				new Varien_Object (
					array_merge (
						array (
							'code' => 3
							,
							$this->getRequestKeyOrderIncrementId() =>
								$this->getRequestValueOrderIncrementId()
							,
							'comment' =>
								df_text()->escapeHtml (
									$e->getMessage()
								)

							,
							$this->getRequestKeySignature() => $this->getResponseSignature (3)
						)
						,
							$this->isItPreliminaryNotification()
						?
							array ()
						:
							array (
								$this->getRequestKeyServicePaymentId() =>
									$this->getRequestValueServicePaymentId()
								,
								'order_id' => $this->getRequestValueOrderIncrementId()
							)
					)

				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getResponseTextForSuccess () {

		/** @var string $result  */
		$result =
			$this->responseObjectToXml (
				new Varien_Object (
					array_merge (
						array (
							'code' => 0
							,
							$this->getRequestKeyOrderIncrementId() =>
								$this->getRequestValueOrderIncrementId()
							,
							'comment' => 'OK'
							,
							$this->getRequestKeySignature() => $this->getResponseSignature (0)
						)
						,
							$this->isItPreliminaryNotification()
						?
							array ()
						:
							array (
								$this->getRequestKeyServicePaymentId() =>
									$this->getRequestValueServicePaymentId()
								,
								'order_id' => $this->getRequestValueOrderIncrementId()
							)

					)

				)
			)
		;


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
				$this->getRequestValueServicePaymentState ()
				,
				$this->getRequestValueOrderIncrementId()
			)
		;


		if (!$this->isItPreliminaryNotification()) {
			$signatureParams []= $this->getRequestValueServicePaymentId();
		}


		$signatureParams =
			array_merge (
				$signatureParams
				,
				array (
					$this->getRequestValuePaymentAmountAsString()
					,
					$this->getRequestValuePaymentCurrencyCode()
					,
					$this->getResponsePassword()
				)
			)
		;


		df_assert_array ($signatureParams);


		/** @var string $result  */
		$result =
			df_helper()->onPay()->generateSignature (
				$signatureParams
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
			in_array (
				$this->getRequestValueServicePaymentState ()
				,
				array (
					self::PAYMENT_STATE__CHECK
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
					self::PAYMENT_STATE__CHECK =>
						'Платёжная система запрашивает у магазина подтверждение необходимости проведения платежа'
				)
				,
				$code
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param int $code
	 * @return string
	 */
	private function getResponseSignature ($code) {

		df_param_integer ($code, 0);

		/** @var array $signatureParams  */
		$signatureParams =
			array (
				$this->getRequestValueServicePaymentState ()
				,
				$this->getRequestValueOrderIncrementId()
			)
		;


		if (!$this->isItPreliminaryNotification()) {

			$signatureParams =
				array_merge (
					$signatureParams
					,
					array (
						$this->getRequestValueServicePaymentId()
						,
						$this->getRequestValueOrderIncrementId()
					)
				)
			;
		}


		$signatureParams =
			array_merge (
				$signatureParams
				,
				array (
					$this->getRequestValuePaymentAmountAsString()
					,
					$this->getRequestValuePaymentCurrencyCode()
					,
					$code
					,
					$this->getResponsePassword()
				)
			)
		;


		df_assert_array ($signatureParams);


		/** @var string $result  */
		$result =
			df_helper()->onPay()->generateSignature (
				$signatureParams
			)
		;


		df_assert_string ($result);

		return $result;

	}






	/**
	 * @param Varien_Object $responseAsVarienObject
	 * @return string
	 */
	private function responseObjectToXml (Varien_Object $responseAsVarienObject) {

		/** @var string $result  */
		$result =
			$responseAsVarienObject->toXml (
				/**
				 * Все свойства
				 */
				array ()

				,
				/**
				 * Корневой тэг
				 */
				'result'

				,
				/**
				 * добавить <?xml version="1.0" encoding="UTF-8"?>
				 */
				true

				,
				/**
				 * Запрещаем добавление CDATA,
				 */
				false
			)
		;


		df_result_string ($result);

		return $result;

	}





	const PAYMENT_STATE__CHECK = 'check';
	const PAYMENT_STATE__PAID = 'paid';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_OnPay_Model_Action_Confirm';
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


