<?php


class Df_RbkMoney_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {


	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'orderId';

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
			/**
			 * Даже в случае сбоя отсылаем код успешного завершения операции,
			 * иначе RBK Money замучает нас повторными запросами.
			 */
			self::RESPONSE_TEXT__SUCCESS
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
				$this->getRequestValueOrderIncrementId()
				,
				$this->getRequestValuePaymentDescription()
				,
				$this->getRequestValueShopAccountId()
				,
				$this->getRequestValuePaymentAmountAsString()
				,
				$this->getRequestValuePaymentCurrencyCode()
				,
				$this->getRequestValueServicePaymentState ()
				,
				$this->getRequestValueCustomerName()
				,
				$this->getRequestValueCustomerEmail()
				,
				$this->getRequestValueServicePaymentDate ()
				,
				$this->getResponsePassword()
			)
		;



		df_assert_array ($signatureParams);



		/** @var string $result  */
		$result =
			md5 (
				implode (
					self::SIGNATURE_SEPARATOR
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
				self::PAYMENT_STATE__PROCESSING
			===
				intval (
					$this->getRequestValueServicePaymentState()
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

		/*
		$this->getOrder()
			->addStatusHistoryComment(
				df_helper()->rbkMoney()->__ (
					self::T__PAYMENT_STATE__PROCESSING
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
		*/

		return $this;

	}



	/**
	 * @return string
	 */
	private function getRequestKeyPaymentDescription () {

		/** @var string $result  */
		$result = $this->getConst (self::CONFIG_KEY__PAYMENT__DESCRIPTION);

		df_result_string ($result);

		return $result;
	}



	/**
	 * @return string
	 */
	private function getRequestKeyShopAccountId () {

		/** @var string $result  */
		$result = $this->getConst (self::CONFIG_KEY__SHOP__ACCOUNT_ID);

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	private function getRequestValuePaymentDescription () {

		/** @var string $result  */
		$result =
			$this->getRequest()->getParam (
				$this->getRequestKeyPaymentDescription ()
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	private function getRequestValueShopAccountId () {

		/** @var string $result  */
		$result =
			$this->getRequest()->getParam (
				$this->getRequestKeyShopAccountId ()
			)
		;

		df_result_string ($result);

		return $result;
	}





	const CONFIG_KEY__PAYMENT__DESCRIPTION = 'payment/description';
	const CONFIG_KEY__SHOP__ACCOUNT_ID = 'shop/account-id';



	const PAYMENT_STATE__PROCESSED = 5;
	const PAYMENT_STATE__PROCESSING = 3;



	const RESPONSE_TEXT__SUCCESS = 'OK';

	const SIGNATURE_SEPARATOR = '::';


	const T__PAYMENT_STATE__PROCESSING = 'Покупатель находится на сайте RBK Money';







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RbkMoney_Model_Action_Confirm';
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


