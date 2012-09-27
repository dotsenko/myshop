<?php


class Df_EasyPay_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



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
		$result = 'order_mer_code';

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
				$this->getRequestValueOrderIncrementId ()
				,
				/**
				 * Обратите внимание, что хотя размер платежа всегда является целым числом,
				 * но EasyPay присылает его в формате с двумя знаками после запятой.
				 * Например: «103.00», а не «103».
				 *
				 * Поэтому не используем $this->getRequestValuePaymentAmount()->getAsInteger()
				 */
				$this->getRequest()->getParam ('sum')
				,
				$this->getRequestValueShopId()
				,
				$this->getRequest()->getParam ('card')
				,
				$this->getRequestValueServicePaymentDate()
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
	protected function processResponseForSuccess () {

		parent::processResponseForSuccess();

		$this->getResponse()->setRawHeader ('HTTP/1.0 200 OK');

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_EasyPay_Model_Action_Confirm';
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


