<?php

class Df_Speed_Helper_Settings_General extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function disableVisitorLogging () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_speed/general/disable_visitor_logging'
			)
		;

		df_result_boolean ($result);

		return $result;
	}







	/**
	 * @return boolean
	 */
	public function enableZendDateCaching () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_speed/general/enable_zend_date_caching'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Speed_Helper_Settings_General';
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