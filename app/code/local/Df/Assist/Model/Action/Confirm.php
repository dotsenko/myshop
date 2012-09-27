<?php


class Df_Assist_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'ordernumber';

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
		$result = $this->getResponseBlockForError($e)->toHtml();

		df_result_string ($result);

		return $result;

	}



	/**
	 * @override
	 * @return string
	 */
	protected function getResponseTextForSuccess () {

		/** @var string $result  */
		$result = $this->getResponseBlockForSuccess()->toHtml();

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
					strtoupper (
						implode (
							Df_Core_Const::T_EMPTY
							,
							array (
								md5 (
									$this->getResponsePassword()
								)
								,
								md5 (
									implode (
										Df_Core_Const::T_EMPTY
										,
										array (
											$this->getRequestValueShopId()
											,
											$this->getRequestValueOrderIncrementId()
											,
											$this->getRequestValuePaymentAmountAsString()
											,
											$this->getRequestValuePaymentCurrencyCode()
											,
											$this->getRequestValueServicePaymentState()
										)
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
	 * @return Df_Assist_Block_Api_PaymentConfirmation_Error
	 */
	private function getResponseBlockForError (Exception $e) {

		if (!isset ($this->_responseBlockForError)) {

			/** @var Df_Assist_Block_Api_PaymentConfirmation_Error $result  */
			$result =
				df_block (
					Df_Assist_Block_Api_PaymentConfirmation_Error::getNameInMagentoFormat()
					,
					null
					,
					array (
						Df_Assist_Block_Api_PaymentConfirmation_Error::PARAM__EXCEPTION => $e

					)
				)
			;


			df_assert ($result instanceof Df_Assist_Block_Api_PaymentConfirmation_Error);

			$this->_responseBlockForError = $result;

		}


		df_assert ($this->_responseBlockForError instanceof Df_Assist_Block_Api_PaymentConfirmation_Error);

		return $this->_responseBlockForError;

	}


	/**
	* @var Df_Assist_Block_Api_PaymentConfirmation_Error
	*/
	private $_responseBlockForError;	
	




	/**
	 * @return Df_Assist_Block_Api_PaymentConfirmation_Success
	 */
	private function getResponseBlockForSuccess () {

		if (!isset ($this->_responseBlockForSuccess)) {

			/** @var Df_Assist_Block_Api_PaymentConfirmation_Success $result  */
			$result =
				df_block (
					Df_Assist_Block_Api_PaymentConfirmation_Success::getNameInMagentoFormat()
					,
					null
					,
					array (
						Df_Assist_Block_Api_PaymentConfirmation_Success::PARAM__BILL_NUMBER =>
							$this->getRequestValueServicePaymentId()
						,
						Df_Assist_Block_Api_PaymentConfirmation_Success::PARAM__PACKET_DATE =>
							$this->getRequestValueServicePaymentDate()
					)
				)
			;


			df_assert ($result instanceof Df_Assist_Block_Api_PaymentConfirmation_Success);

			$this->_responseBlockForSuccess = $result;

		}


		df_assert ($this->_responseBlockForSuccess instanceof Df_Assist_Block_Api_PaymentConfirmation_Success);

		return $this->_responseBlockForSuccess;

	}


	/**
	* @var Df_Assist_Block_Api_PaymentConfirmation_Success
	*/
	private $_responseBlockForSuccess;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Assist_Model_Action_Confirm';
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


