<?php


class Df_Catalog_Helper_Eav extends Mage_Catalog_Helper_Data {


	
	/**
	 * @return Mage_Eav_Model_Entity
	 */
	public function getProductEntity () {
	
		if (!isset ($this->_productEntity)) {
	
			/** @var Mage_Eav_Model_Entity $result  */
			$result = 		
				Mage::getModel('eav/entity')
			;
			
			df_assert ($result instanceof Mage_Eav_Model_Entity);
			
			
			$result->setType ('catalog_product');
	
	
			df_assert ($result instanceof Mage_Eav_Model_Entity);
	
			$this->_productEntity = $result;
	
		}
	
	
		df_assert ($this->_productEntity instanceof Mage_Eav_Model_Entity);
	
		return $this->_productEntity;
	
	}
	
	
	/**
	* @var Mage_Eav_Model_Entity
	*/
	private $_productEntity;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Eav';
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

