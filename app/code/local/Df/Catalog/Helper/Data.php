<?php


class Df_Catalog_Helper_Data extends Mage_Core_Helper_Abstract {




	/**
	 * @return Df_Catalog_Helper_Assert
	 */
	public function assert () {

		/** @var Df_Catalog_Helper_Assert $result  */
		$result =
			Mage::helper (Df_Catalog_Helper_Assert::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Catalog_Helper_Assert);

		return $result;

	}
	
	

	/**
	 * @return Df_Catalog_Helper_Category
	 */
	public function category () {

		/** @var Df_Catalog_Helper_Category $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Catalog_Helper_Category $result  */
			$result = Mage::helper (Df_Catalog_Helper_Category::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Catalog_Helper_Category);
		}

		return $result;
	}	





	/**
	 * @return Df_Catalog_Helper_Check
	 */
	public function check () {

		/** @var Df_Catalog_Helper_Check $result  */
		$result =
			Mage::helper (Df_Catalog_Helper_Check::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Catalog_Helper_Check);

		return $result;

	}




	/**
	 * @return Df_Catalog_Model_Resource_Setup
	 */
	public function getSetup () {

		/** @var Df_Catalog_Model_Resource_Setup $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Catalog_Model_Resource_Setup $result  */
			$result =
				Mage::getResourceModel(
					Df_Catalog_Model_Resource_Setup::getNameInMagentoFormat()
					,
					'df_catalog_setup'
				)
			;

			df_assert ($result instanceof Df_Catalog_Model_Resource_Setup);

		}

		return $result;

	}





	/**
	 * @return Df_Catalog_Helper_Eav
	 */
	public function eav () {

		/** @var Df_Catalog_Helper_Eav $result  */
		$result =
			Mage::helper (Df_Catalog_Helper_Eav::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Catalog_Helper_Eav);

		return $result;

	}




	/**
	 * @return Df_Catalog_Helper_Product
	 */
	public function product () {

		/** @var Df_Catalog_Helper_Product $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Catalog_Helper_Product $result  */
			$result = Mage::helper (Df_Catalog_Helper_Product::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Catalog_Helper_Product);
		}

		return $result;
	}





	/**
	 * @return Df_Catalog_Helper_Product_Dataflow
	 */
	public function product_dataflow () {

		/** @var Df_Catalog_Helper_Product_Dataflow $result  */
		$result =
			Mage::helper (Df_Catalog_Helper_Product_Dataflow::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Catalog_Helper_Product_Dataflow);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Data';
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