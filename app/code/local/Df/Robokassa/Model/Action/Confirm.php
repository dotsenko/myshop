<?php


class Df_Robokassa_Model_Action_Confirm extends Df_Payment_Model_Action_Confirm {



	/**
	 * Использовать getConst нельзя из-за рекурсии.
	 *
	 * @override
	 * @return string
	 */
	protected function getRequestKeyOrderIncrementId () {

		/** @var string $result  */
		$result = 'InvId';

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
		$result =
			sprintf (
				implode (
					Df_Core_Const::T_EMPTY
					,
					array (
						self::RESPONSE_TEXT__SUCCESS__PREFIX
						,
						$this->getRequestValueOrderIncrementId()
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

		/** @var string $result  */
		$result =
			md5 (
				implode (
					self::SIGNATURE_PARTS_SEPARATOR
					,
					array (
						$this->getRequestValuePaymentAmount()->getAsString()
						,
						$this->getRequestValueOrderIncrementId()
						,
						$this->getResponsePassword()
					)
				)
			)
		;


		df_assert_string ($result);

		return $result;

	}




	const RESPONSE_TEXT__SUCCESS__PREFIX = 'OK';

	const SIGNATURE_PARTS_SEPARATOR = Df_Robokassa_Model_Request_Payment::SIGNATURE_PARTS_SEPARATOR;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Robokassa_Model_Action_Confirm';
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


