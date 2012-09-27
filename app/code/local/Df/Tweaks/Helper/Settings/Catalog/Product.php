<?php


class Df_Tweaks_Helper_Settings_Catalog_Product extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Tweaks_Helper_Settings_Catalog_Product_List
	 */
	public function _list () {

		/** @var Df_Tweaks_Helper_Settings_Catalog_Product_List $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Catalog_Product_List::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Catalog_Product_List);

		return $result;

	}


	/**
	 * @return Df_Tweaks_Helper_Settings_Catalog_Product_View
	 */
	public function view () {

		/** @var Df_Tweaks_Helper_Settings_Catalog_Product_View $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Catalog_Product_View::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Catalog_Product_View);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Catalog_Product';
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