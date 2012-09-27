<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Category extends Df_1C_Model_Cml2_Import_Data_Entity {
	
	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Categories
	 */
	public function getChildren () {
	
		if (!isset ($this->_children)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Categories $result  */
			$result = 
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Categories::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Categories
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
					)
				)
			;
	
	
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Categories);
	
			$this->_children = $result;
	
		}
	
	
		df_assert ($this->_children instanceof Df_1C_Model_Cml2_Import_Data_Collection_Categories);
	
		return $this->_children;
	
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Categories
	*/
	private $_children;	
	
	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Category';
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

