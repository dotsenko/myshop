<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Text
	extends Df_1C_Model_Cml2_Import_Data_Entity_Attribute {


	/**
	 * @override
	 * @return string
	 */
	public function getBackendModel() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @override
	 * @return string
	 */
	public function getBackendType() {
		return 'varchar';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getFrontendInput() {
		return 'text';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getSourceModel() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Text';
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
