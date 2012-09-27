<?php

class Df_Logging_Helper_Settings_Archiving extends Df_Core_Helper_Settings {


	/**
	 * @return int
	 */
	public function getFrequency () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_tweaks_admin/logging__archiving/frequency'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @return int
	 */
	public function getLifetime () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_tweaks_admin/logging__archiving/lifetime'
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
		return 'Df_Logging_Helper_Settings_Archiving';
	}


	/**
	 * Например, для класса Df_LoggingRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_logging_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}
