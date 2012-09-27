<?php


class Df_Moneta_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'MNT_TRANSACTION_ID';

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
				$this->getRequestValueOrderIncrementId()
				,
				$this->getRequestValueServicePaymentId()
				,
				$this->getRequestValuePaymentAmountAsString()
				,
				$this->getRequestValuePaymentCurrencyCode()
				,
				$this->getRequestValuePaymentTest ()
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




	const RESPONSE_TEXT__SUCCESS = 'SUCCESS';
	const RESPONSE_TEXT__FAIL = 'FAIL';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Moneta_Model_Action_Confirm';
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


