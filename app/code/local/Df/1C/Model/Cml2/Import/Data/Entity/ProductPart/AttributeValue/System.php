<?php

class Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System
	extends Df_1C_Model_Cml2_Import_Data_Entity {


	/**
	 * @override
	 * @return string
	 */
	public function getExternalId () {

		/** @var string $result  */
		$result = $this->getName();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getValue () {

		/** @var string $result  */
		$result = $this->getEntityParam ('Значение');

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System';
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

