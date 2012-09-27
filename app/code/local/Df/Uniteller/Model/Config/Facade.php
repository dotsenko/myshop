<?php

class Df_Uniteller_Model_Config_Facade extends Df_Payment_Model_Config_Facade {



	/**
	 * @override
	 * @return Df_Uniteller_Model_Config_Service
	 */
	public function service () {

		/** @var Df_Uniteller_Model_Config_Service $result  */
		$result = parent::service();

		df_assert ($result instanceof Df_Uniteller_Model_Config_Service);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Uniteller_Model_Config_Facade';
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


