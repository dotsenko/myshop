<?php


class Df_Tweaks_Helper_Settings_Poll extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getRemoveFromAll () {
		return
			Mage::getStoreConfig (
				'df_tweaks/poll/remove_from_all'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveFromFrontpage () {
		return
			Mage::getStoreConfig (
				'df_tweaks/poll/remove_from_frontpage'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveFromCatalogProductList () {
		return
			Mage::getStoreConfig (
				'df_tweaks/poll/remove_from_catalog_product_list'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveFromCatalogProductView () {
		return
			Mage::getStoreConfig (
				'df_tweaks/poll/remove_from_catalog_product_view'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Poll';
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