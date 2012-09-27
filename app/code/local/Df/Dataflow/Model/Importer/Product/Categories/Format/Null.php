<?php


class Df_Dataflow_Model_Importer_Product_Categories_Format_Null
	extends Df_Dataflow_Model_Importer_Product_Categories_Parser {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Categories_Format_Null';
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








	/**
	 * @return array
	 */
	public function getPaths () {
		return array ();
	}

}
 
