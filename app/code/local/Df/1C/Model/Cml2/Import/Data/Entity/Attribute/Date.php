<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Date
	extends Df_1C_Model_Cml2_Import_Data_Entity_Attribute {



	/**
	 * @override
	 * @param string $valueAsString
	 * @return string
	 */
	public function convertValueToMagentoFormat ($valueAsString) {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!df_empty($valueAsString)) {

			/** @var Zend_Date $date */
			$date =
				new Zend_Date (
					$valueAsString
					,
					'dd.MM.yyyy H:mm:ss'
				)
			;

			df_assert ($date instanceof Zend_Date);


			/** @var string $result  */
			$result =
				$date->toString (
					Varien_Date::DATETIME_INTERNAL_FORMAT
				)
			;

		}

		df_result_string ($result);

		return $valueAsString;

	}




	/**
	 * @override
	 * @return string
	 */
	public function getBackendModel() {
		return 'eav/entity_attribute_backend_datetime';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getBackendType() {
		return 'datetime';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getFrontendInput() {
		return 'date';
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
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Date';
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
