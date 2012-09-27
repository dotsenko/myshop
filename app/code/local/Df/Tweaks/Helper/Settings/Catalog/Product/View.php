<?php


class Df_Tweaks_Helper_Settings_Catalog_Product_View extends Df_Core_Helper_Settings {



	/**
	 * @return Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku
	 */
	public function sku () {

		/** @var Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku $result  */
		$result =
			Mage::helper (
				Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku);

		return $result;

	}



	/**
	 * @return boolean
	 */
	public function getHideAddToWishlist () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_add_to_wishlist'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAddToCompare () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_add_to_compare'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAddToCart () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_add_to_cart'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideEmptyAttributes () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_empty_attributes'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getHidePrice () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_price'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideRating () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_rating'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideReviewsLink () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_reviews_link'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAddReviewLink () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_add_review_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getHideShortDescription () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_short_description'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideTags () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_tags'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideAvailability () {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_availability'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getHideEmailToFriend() {
		return
			Mage::getStoreConfig (
				'df_tweaks/catalog_product_view/hide_email_to_friend'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Catalog_Product_View';
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