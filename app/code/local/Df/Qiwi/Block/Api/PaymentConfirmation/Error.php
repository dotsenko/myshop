<?php

class Df_Qiwi_Block_Api_PaymentConfirmation_Error extends Df_Core_Block_Template {



	/**
	 * @return int
	 */
	public function getFirstCode () {

		/** @var int $result  */
		$result = 1;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getSecondCode () {

		/** @var int $result  */
		$result = 0;

		df_result_integer ($result);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result = self::RM__TEMPLATE;

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return Exception
	 */
	private function getException () {

		/** @var Exception $result  */
		$result = $this->cfg (self::PARAM__EXCEPTION);

		df_assert ($result instanceof Exception);

		return $result;

	}




	const PARAM__EXCEPTION = 'exception';

	const RM__TEMPLATE = 'df/qiwi/api/payment-confirmation/error.xml';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Qiwi_Block_Api_PaymentConfirmation_Error';
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


