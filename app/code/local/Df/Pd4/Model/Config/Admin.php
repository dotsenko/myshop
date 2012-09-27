<?php

class Df_Pd4_Model_Config_Admin extends Df_Payment_Model_Config_Area_Admin {



	/**
	 * @return string
	 */
	public function getPaymentPurposeTemplate () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__PAYMENT_PURPOSE_TEMPLATE);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientBankAccountNumber () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_BANK_ACCOUNT_NUMBER);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientBankId () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_BANK_ID);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientBankLoro () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_BANK_LORO);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientBankName () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_BANK_NAME);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientName () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_NAME);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getRecipientTaxNumber () {

		/** @var string $result  */
		$result = $this->getVar (self::KEY__VAR__RECIPIENT_RECIPIENT_TAX_NUMBER);

		df_result_string ($result);

		return $result;

	}




	const KEY__VAR__PAYMENT_PURPOSE_TEMPLATE = 'payment_purpose_template';
	const KEY__VAR__RECIPIENT_BANK_ACCOUNT_NUMBER = 'recipient_bank_account_number';
	const KEY__VAR__RECIPIENT_BANK_ID = 'recipient_bank_id';
	const KEY__VAR__RECIPIENT_BANK_LORO = 'recipient_bank_loro';
	const KEY__VAR__RECIPIENT_BANK_NAME = 'recipient_bank_name';
	const KEY__VAR__RECIPIENT_NAME = 'recipient_name';
	const KEY__VAR__RECIPIENT_RECIPIENT_TAX_NUMBER = 'recipient_tax_number';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Model_Config_Admin';
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


