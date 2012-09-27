<?php

class Df_1C_Model_Cml2 extends Df_Core_Model_Abstract {


	/**
	 * Данный метод никак не связан данным с классом,
	 * однако включён в класс для удобного доступа объектов класса к реестру
	 * (чтобы писать $this->getRegistry() вместо df_helper()->_1c()->cml2()->registry())
	 *
	 * @return Df_1C_Helper_Cml2_Registry
	 */
	protected function getRegistry () {
		return df_helper()->_1c()->cml2()->registry();
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2';
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


