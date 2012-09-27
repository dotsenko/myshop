<?php

class Df_Qiwi_Block_Api_PaymentConfirmation_Success extends Df_Core_Block_Template {



	/**
	 * @return string
	 */
	public function getBillNumber () {

		/** @var string $result  */
		$result = self::PARAM__BILL_NUMBER;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getPacketDate () {

		/** @var string $result  */
		$result = self::PARAM__PACKET_DATE;

		df_result_string ($result);

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



	const PARAM__BILL_NUMBER = 'bill_number';
	const PARAM__PACKET_DATE = 'packet_date';

	const RM__TEMPLATE = 'df/qiwi/api/payment-confirmation/success.xml';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Qiwi_Block_Api_PaymentConfirmation_Success';
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


