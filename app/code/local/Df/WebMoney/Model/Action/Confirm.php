<?php


class Df_WebMoney_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {




	/**
	 * @override
	 * @return Df_WebMoney_Model_Action_Confirm
	 */
	public function process () {

		try {

			df_assert (
				(0 < count ($this->getRequest()->getParams()))
				,
				"Платёжная система WebMoney прислала подтверждение оплаты безо всяких параметров.
				\nВидимо, администратор магазина некачественно настроил Личный кабинет WebMoney:
				забыл включить опцию «Передавать параметры в предварительном запросе».
				"
			)
			;

			parent::process();
		}

		catch (Exception $e) {

			$this->processException ($e);

		}


		return $this;

	}








	/**
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 * @throws Mage_Core_Exception
	 */
	protected function checkSignature () {

		if (!$this->isItPreliminaryNotification()) {
			parent::checkSignature();
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
		$result = 'LMI_PAYMENT_NO';

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
				$this->getRequestValueServicePaymentTest()
				,
				$this->getRequest()->getParam ('LMI_SYS_INVS_NO')
				,
				$this->getRequestValueServicePaymentId()
				,
				$this->getRequestValueServicePaymentDate()
				,
				$this->getResponsePassword()
				,
				$this->getRequestValueServiceCustomerAccountId()
				,
				$this->getRequestValueServiceCustomerId()
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
				1
			===
				intval (
					$this->getRequest()->getParam ('LMI_PREREQUEST')
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
				'Предварительная проверка платёжной системой работоспособности магазина'
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
	 * Кошелек покупателя
	 *
	 * @return string
	 */
	private function getRequestKeyServiceCustomerAccountId () {

		/** @var string $result  */
		$result = $this->getConst (self::CONFIG_KEY__PAYMENT_SERVICE__CUSTOMER__ACCOUNT_ID);

		df_result_string ($result);

		return $result;
	}




	/**
	 * WMId покупателя
	 *
	 * @return string
	 */
	private function getRequestKeyServiceCustomerId () {

		/** @var string $result  */
		$result = $this->getConst (self::CONFIG_KEY__PAYMENT_SERVICE__CUSTOMER__ID);

		df_result_string ($result);

		return $result;
	}




	/**
	 * Указывает, в каком режиме выполнялась обработка запроса на платеж.
	 * Может принимать два значения:
	 * 	0:	Платеж выполнялся в реальном режиме,
	 * 		средства переведены с кошелька покупателя
	 * 		на кошелек продавца;
	 * 	1:	Платеж выполнялся в тестовом режиме,
	 * 		средства реально не переводились.
	 *
	 * @return string
	 */
	private function getRequestKeyServicePaymentTest () {

		/** @var string $result  */
		$result = $this->getConst (self::CONFIG_KEY__PAYMENT_SERVICE__PAYMENT__TEST);

		df_result_string ($result);

		return $result;
	}




	/**
	 * Кошелек покупателя
	 *
	 * @return string
	 */
	private function getRequestValueServiceCustomerAccountId () {

		/** @var string $result  */
		$result =
			$this->getRequest()->getParam (
				$this->getRequestKeyServiceCustomerAccountId ()
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * WMId покупателя
	 *
	 * @return string
	 */
	private function getRequestValueServiceCustomerId () {

		/** @var string $result  */
		$result =
			$this->getRequest()->getParam (
				$this->getRequestKeyServiceCustomerId ()
			)
		;

		df_result_string ($result);

		return $result;
	}







	/**
	 * Указывает, в каком режиме выполнялась обработка запроса на платеж.
	 * Может принимать два значения:
	 * 	0:	Платеж выполнялся в реальном режиме,
	 * 		средства переведены с кошелька покупателя
	 * 		на кошелек продавца;
	 * 	1:	Платеж выполнялся в тестовом режиме,
	 * 		средства реально не переводились.
	 *
	 * @return string
	 */
	private function getRequestValueServicePaymentTest () {

		/** @var string $result  */
		$result =
			$this->getRequest()->getParam (
				$this->getRequestKeyServicePaymentTest ()
			)
		;

		df_result_string ($result);

		return $result;
	}






	/**
	 * Кошелек покупателя
	 */
	const CONFIG_KEY__PAYMENT_SERVICE__CUSTOMER__ACCOUNT_ID = 'payment_service/customer/account-id';

	/**
	 * WMId покупателя
	 */
	const CONFIG_KEY__PAYMENT_SERVICE__CUSTOMER__ID = 'payment_service/customer/id';



	/**
	 * Указывает, в каком режиме выполнялась обработка запроса на платеж.
	 * Может принимать два значения:
	 * 	0:	Платеж выполнялся в реальном режиме,
	 * 		средства переведены с кошелька покупателя
	 * 		на кошелек продавца;
	 * 	1:	Платеж выполнялся в тестовом режиме,
	 * 		средства реально не переводились.
	 */
	const CONFIG_KEY__PAYMENT_SERVICE__PAYMENT__TEST = 'payment_service/payment/test';





	const RESPONSE_TEXT__SUCCESS = 'YES';
	const RESPONSE_TEXT__FAIL = 'NO';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebMoney_Model_Action_Confirm';
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


