<?php

class Df_1C_Model_Cml2_Import_Data_Collection_Orders
	extends Df_1C_Model_Cml2_Import_Data_Collection {


	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_Order::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {
		return
			array (
				Df_Core_Const::T_EMPTY
				,
				'КоммерческаяИнформация'
				,
				'Документ'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_Orders';
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

