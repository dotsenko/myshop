<?php

class Df_Logging_Helper_Settings extends Df_Core_Helper_Settings {



	/**
	 * @return Df_Logging_Helper_Settings_Archiving
	 */
	public function archiving () {

		/** @var Df_Logging_Helper_Settings_Archiving $result  */
		static $result;

		if (!isset ($result)) {
			$result =
				Mage::helper (
					Df_Logging_Helper_Settings_Archiving::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Logging_Helper_Settings_Archiving);
		}

		return $result;
	}





	/**
	 * @return string|null
	 */
	public function getActions () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_tweaks_admin/logging__actions/actions'
			)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function isEnabled () {


		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/logging__archiving/enabled'
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
		return 'Df_Logging_Helper_Settings';
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