<?php

class Df_Garantpost_Model_Config_Facade extends Df_Shipping_Model_Config_Facade {


	/**
	 * @override
	 * @return Df_Garantpost_Model_Config_Area_Service
	 */
	public function service () {

		/** @var Df_Garantpost_Model_Config_Area_Service $result  */
		$result = parent::service();

		df_assert ($result instanceof Df_Garantpost_Model_Config_Area_Service);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Config_Facade';
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

