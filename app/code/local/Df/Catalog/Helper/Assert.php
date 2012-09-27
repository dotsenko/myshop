<?php


class Df_Catalog_Helper_Assert extends Mage_Core_Helper_Abstract {
	

	
	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Catalog_Helper_Assert
	 */
	public function categoryCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->catalog()->check()->categoryCollection ($collection)
			)
		;

		return $this;

	}	
	
	
	
	
	
	/**
	 * @var Mage_Core_Model_Resource_Abstract $resource
	 * @return Df_Catalog_Helper_Assert
	 */
	public function categoryResource (Mage_Core_Model_Resource_Abstract $resource) {

			df_assert (
				df_helper()->catalog()->check()->categoryResource ($resource)
			)
		;

		return $this;

	}





	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Catalog_Helper_Assert
	 */
	public function productAttributeCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->catalog()->check()->productAttributeCollection ($collection)
			)
		;

		return $this;

	}
	
	
	
	


	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Catalog_Helper_Assert
	 */
	public function productCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->catalog()->check()->productCollection ($collection)
			)
		;

		return $this;

	}
	
	
	
	
	
	/**
	 * @var Mage_Core_Model_Resource_Abstract $resource
	 * @return Df_Catalog_Helper_Assert
	 */
	public function productResource (Mage_Core_Model_Resource_Abstract $resource) {

			df_assert (
				df_helper()->catalog()->check()->productResource ($resource)
			)
		;

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Assert';
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