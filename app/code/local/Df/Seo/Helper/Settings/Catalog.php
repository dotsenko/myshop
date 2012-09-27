<?php


class Df_Seo_Helper_Settings_Catalog extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Seo_Helper_Settings_Catalog_Category
	 */
	public function category () {

		/** @var Df_Seo_Helper_Settings_Catalog_Category $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Seo_Helper_Settings_Catalog_Category $result  */
			$result = Mage::helper (Df_Seo_Helper_Settings_Catalog_Category::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Seo_Helper_Settings_Catalog_Category);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Settings_Catalog';
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
