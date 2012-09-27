<?php

class Df_CustomerBalance_Helper_Settings extends Df_Core_Helper_Settings {



	/**
	 * @return boolean
	 */
	public function isEnabled () {


		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_customer/balance/enabled'
			)
		;

		df_result_boolean ($result);


		return $result;

	}





	/**
	 * @return boolean
	 */
	public function needShowHistory () {


		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_customer/balance/show_history'
			)
		;

		df_result_boolean ($result);


		return $result;

	}




	/**
	 * @return string
	 */
	public function getTransactionalEmailSender () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				Mage::getStoreConfig (
					'df_customer/balance/email_identity'
				)
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getTransactionalEmailTemplateId () {


		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_customer/balance/email_template'
				)
			)
		;

		df_result_integer ($result);


		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_CustomerBalance_Helper_Settings';
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