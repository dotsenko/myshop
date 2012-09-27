<?php

class Df_AdminNotification_Helper_Settings extends Df_Core_Helper_Settings {

	/**
	 * @return boolean
	 */
	public function getFixReminder () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/system_notifications/fix_reminder'
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
		return 'Df_AdminNotification_Helper_Settings';
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