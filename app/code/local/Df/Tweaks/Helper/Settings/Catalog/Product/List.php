<?php


class Df_Tweaks_Helper_Settings_Catalog_Product_List extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getHideAddToWishlist () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_add_to_wishlist'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAddToCompare () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_add_to_compare'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAddToCart () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_add_to_cart'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHidePrice () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_price'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideRating () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_rating'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideReviews () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/hide_reviews'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getReplaceAddToCartWithMore () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_list/replace_add_to_cart_with_more'
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Catalog_Product_List';
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