<?php


class Df_Catalog_Helper_Check extends Mage_Core_Helper_Abstract {

	
	
	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function categoryCollection (Varien_Data_Collection_Db $collection) {

		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Catalog_Model_Resource_Category_Collection')
			?
				(
					(
							$collection
						instanceof
							Mage_Catalog_Model_Resource_Category_Collection
					)
						||
					(
							$collection
						instanceof
							Mage_Catalog_Model_Resource_Category_Flat_Collection
					)
				)
			:
				(
					(
							$collection
						instanceof
							Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
					)
						||
					(
							$collection
						instanceof
							Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Flat_Collection
					)
				)
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @var Mage_Core_Model_Resource_Abstract $resource
	 * @return bool
	 */
	public function categoryResource (Mage_Core_Model_Resource_Abstract $resource) {

		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Catalog_Model_Resource_Category')
			?
				(
						$resource
					instanceof
						Mage_Catalog_Model_Resource_Category
				)
			:
				(
						$resource
					instanceof
						Mage_Catalog_Model_Resource_Eav_Mysql4_Category
				)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function productAttributeCollection (Varien_Data_Collection_Db $collection) {


		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Catalog_Model_Resource_Product_Attribute_Collection')
			?
				(
						$collection
					instanceof
						Mage_Catalog_Model_Resource_Product_Attribute_Collection
				)
			:
				(
						$collection
					instanceof
						Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Collection
				)
		;


		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function productCollection (Varien_Data_Collection_Db $collection) {


		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Catalog_Model_Resource_Product_Collection')
			?
				(
						$collection
					instanceof
						Mage_Catalog_Model_Resource_Product_Collection
				)
			:
				(
						$collection
					instanceof
						Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
				)
		;


		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @var Mage_Core_Model_Resource_Abstract $resource
	 * @return bool
	 */
	public function productResource (Mage_Core_Model_Resource_Abstract $resource) {

		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Catalog_Model_Resource_Product')
			?
				(
						$resource
					instanceof
						Mage_Catalog_Model_Resource_Product
				)
			:
				(
						$resource
					instanceof
						Mage_Catalog_Model_Resource_Eav_Mysql4_Product
				)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Check';
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