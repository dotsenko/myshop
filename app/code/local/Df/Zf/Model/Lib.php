<?php


class Df_Zf_Model_Lib extends Df_Core_Model_Lib_Abstract {



	/**
	 * @return void
	 */
	public function init () {
		Mage::helper ('df_zf/lib');
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Zf_Model_Lib';
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