<?php

abstract class Df_1C_Model_Cml2_Import_Data_Entity_Attribute
	extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @param string $valueAsString
	 * @return string
	 */
	public function convertValueToMagentoFormat ($valueAsString) {

		return $valueAsString;

	}



	/**
	 * @return string
	 */
	public function getBackendModel() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @return string
	 */
	public function getBackendType() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @return string
	 */
	public function getFrontendInput() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @return string
	 */
	public function getSourceModel() {
		return Df_Core_Const::T_EMPTY;
	}






	/**
	 * @return string
	 */
	public function getExternalTypeName () {
	
		if (!isset ($this->_externalTypeName)) {
	
			/** @var string $result  */
			$result = 
				self::getExternalTypeNameStatic (
					$this->getSimpleXmlElement()
				)
			;
	
			df_assert_string ($result);
	
			$this->_externalTypeName = $result;
	
		}
	
	
		df_result_string ($this->_externalTypeName);
	
		return $this->_externalTypeName;
	
	}
	
	
	/**
	* @var string
	*/
	private $_externalTypeName;





	/**
	 * @param Varien_Simplexml_Element $entityAsSimpleXMLElement
	 * @return string
	 */
	public static function getExternalTypeNameStatic (
		Varien_Simplexml_Element $entityAsSimpleXMLElement
	) {

		/** @var SimpleXMLElement[]|bool $externalTypeNames */
		$externalTypeNames =
			$entityAsSimpleXMLElement->xpath ('ТипыЗначений/ТипЗначений/Тип')
		;

		df_assert_array ($externalTypeNames);
		df_assert_between (count ($externalTypeNames), 1);


		/** @var string $result */
		$result =
			(string)(df_a ($externalTypeNames, 0))
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Attribute';
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

