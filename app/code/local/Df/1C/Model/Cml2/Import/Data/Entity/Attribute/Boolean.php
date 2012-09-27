<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Boolean
	extends Df_1C_Model_Cml2_Import_Data_Entity_Attribute {


	/**
	 * @override
	 * @param string $valueAsString
	 * @return string
	 */
	public function convertValueToMagentoFormat ($valueAsString) {

		/** @var string $result  */
		$result =
			df_a (
				array (
					'true' => '1'
					,
					'false' => '0'
				)
				,
				$valueAsString
				,
				Df_Core_Const::T_EMPTY
			)
		;

		df_result_string ($result);

		return $valueAsString;

	}




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
		return 'int';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getFrontendInput() {
		return 'select';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getSourceModel() {
		return 'eav/entity_attribute_source_boolean';
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Boolean';
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
