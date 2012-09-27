<?php

class Df_Newsletter_Helper_Settings_Subscription extends Df_Core_Helper_Settings {



	/**
	 * @return boolean
	 */
	public function fixSubscriberStore () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_newsletter/subscription/fix_subscriber_store'
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
		return 'Df_Newsletter_Helper_Settings_Subscription';
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