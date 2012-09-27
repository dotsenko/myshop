<?php


class Df_WalletOne_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {




	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'WMI_PAYMENT_NO';

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
			sprintf (
				self::RESPONSE_TEXT__FAIL
				,
				urlencode (
					$e->getMessage()
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
			$this->getSignatureGenerator()->getSignature()
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
					self::PAYMENT_STATE__PROCESSED
					,
					self::PAYMENT_STATE__PROCESSING
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @override
	 * @return Df_Payment_Model_Action_Confirm
	 * @throws Mage_Core_Exception
	 */
	protected function processOrderCanNotInvoice () {

		/**
		 * Единая Касса любит присылать повторные оповещения об оплате.
		 */
		$this->getOrder()
			->addStatusHistoryComment(
				'Единая Касса повторно прислала оповещение об оплате'
			)
		;


		$this->getResponse ()
			->setBody (
				$this->getResponseTextForSuccess ()
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
				'Покупатель отказался от оплаты'
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
	 * @return Df_WalletOne_Model_Request_SignatureGenerator
	 */
	private function getSignatureGenerator () {

		if (!isset ($this->_signatureGenerator)) {

			/** @var Df_WalletOne_Model_Request_SignatureGenerator $result  */
			$result =
				df_model (
					Df_WalletOne_Model_Request_SignatureGenerator::getNameInMagentoFormat()
					,
					array (
						Df_WalletOne_Model_Request_SignatureGenerator::PARAM__ENCRYPTION_KEY =>
							$this->getServiceConfig()->getResponsePassword()
						,
						Df_WalletOne_Model_Request_SignatureGenerator::PARAM__SIGNATURE_PARAMS =>
							array_diff_key (
 								$this->getRequest()->getParams()
								,
								array (
									$this->getRequestKeySignature() => null
								)
							)

					)
				)
			;


			df_assert ($result instanceof Df_WalletOne_Model_Request_SignatureGenerator);

			$this->_signatureGenerator = $result;

		}


		df_assert ($this->_signatureGenerator instanceof Df_WalletOne_Model_Request_SignatureGenerator);

		return $this->_signatureGenerator;

	}


	/**
	* @var Df_WalletOne_Model_Request_SignatureGenerator
	*/
	private $_signatureGenerator;





	const RESPONSE_TEXT__SUCCESS = 'WMI_RESULT=OK';
	const RESPONSE_TEXT__FAIL = 'WMI_RESULT=CANCEL&WMI_DESCRIPTION=%s';


	const PAYMENT_STATE__PROCESSING = 'Processing';
	const PAYMENT_STATE__PROCESSED = 'Accepted';
	const PAYMENT_STATE__CANCELLED = 'Rejected';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Action_Confirm';
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


