<?php

class Df_1C_Model_Cml2_Import_Data_Collection_Attributes
	extends Df_1C_Model_Cml2_Import_Data_Collection {



	/**
	 * @override
	 * @param Varien_Simplexml_Element $entityAsSimpleXMLElement
	 * @return Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function createItemFromSimpleXmlElement (Varien_Simplexml_Element $entityAsSimpleXMLElement) {

		/** @var string $elementType */
		$elementType =
			Df_1C_Model_Cml2_Import_Data_Entity_Attribute
				::getExternalTypeNameStatic (
					$entityAsSimpleXMLElement
				)
		;

		df_assert_string ($elementType);


		/** @var string $itemClassMf  */
		$itemClassMf =
			df_a (
				$this->getItemTypeMap()
				,
				$elementType
				,
				Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Text::getNameInMagentoFormat()
			)
		;



		/** @var Df_1C_Model_Cml2_Import_Data_Entity $result  */
		$result =
			df_model (
				$itemClassMf
				,
				array (
					Df_1C_Model_Cml2_Import_Data_Entity::PARAM__SIMPLE_XML => $entityAsSimpleXMLElement
				)
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity);

		return $result;

	}

	
	
	
	/**
	 * @return array
	 */
	private function getItemTypeMap () {
	
		if (!isset ($this->_itemTypeMap)) {
	
			/** @var array $result  */
			$result =
				array (
					'Справочник' => Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList
						::getNameInMagentoFormat()
					,
					'Дата' => Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Date
						::getNameInMagentoFormat()
					,
					'Число' => Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Number
						::getNameInMagentoFormat()
					,
					'Булево' => Df_1C_Model_Cml2_Import_Data_Entity_Attribute_Boolean
						::getNameInMagentoFormat()
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_itemTypeMap = $result;
	
		}
	
	
		df_result_array ($this->_itemTypeMap);
	
		return $this->_itemTypeMap;
	
	}
	
	
	/**
	* @var array
	*/
	private $_itemTypeMap;	
	
	



	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_Attribute::getNameInMagentoFormat();
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
				'Классификатор'
				,
				'Свойства'
				,
				'Свойство'
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_Attributes';
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
