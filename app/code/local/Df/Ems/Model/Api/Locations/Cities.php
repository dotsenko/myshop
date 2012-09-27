<?php

class Df_Ems_Model_Api_Locations_Cities extends Df_Ems_Model_Api_Locations_Abstract {



	/**
	 * @override
	 * @return string
	 */
	protected function getLocationType () {
		return 'cities';
	}

	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Api_Locations_Cities';
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


