<?php


class Df_Tweaks_Helper_Settings_Account extends Df_Core_Helper_Settings {



	/**
	 * @return boolean
	 */
	public function getRemoveSectionBillingAgreements () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks/account/remove_section_billing_agreements'
			)
		;

		df_result_boolean ($result);

		return $result;

	}



	/**
	 * @return boolean
	 */
	public function getRemoveSectionRecurringProfiles () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_recurring_profiles'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveSectionProductReviews () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_product_reviews'
			)
		;
	}




	/**
	 * @return boolean
	 */
	public function getRemoveSectionTags () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_tags'
			)
		;
	}




	/**
	 * @return boolean
	 */
	public function getRemoveSectionWishlist () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_wishlist'
			)
		;
	}




	/**
	 * @return boolean
	 */
	public function getRemoveSectionDownloadableProducts () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_downloadable_products'
			)
		;
	}




	/**
	 * @return boolean
	 */
	public function getRemoveSectionNewsletterSubscriptions () {
		return
			Mage::getStoreConfig (
				'df_tweaks/account/remove_section_newsletter_subscriptions'
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Account';
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