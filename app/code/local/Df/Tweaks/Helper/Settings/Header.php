<?php


class Df_Tweaks_Helper_Settings_Header extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getRemoveCartLink () {
		return
			$this->getYesNo (
				'df_tweaks/header/remove_cart_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveCheckoutLink () {
		return
			$this->getYesNo (
				'df_tweaks/header/remove_checkout_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function needRemoveWelcomeForLoggedIn () {
		return
			$this->getYesNo (
				'df_tweaks/header/remove_welcome_for_logged_in'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveWishlistLink () {
		return
			$this->getYesNo (
				'df_tweaks/header/remove_wishlist_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getReplaceAccountLinkTitleWithCustomerName () {
		return
			$this->getYesNo (
				'df_tweaks/header/replace_account_link_title_with_customer_name'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getShowOnlyFirstName () {
		return
			$this->getYesNo (
				'df_tweaks/header/show_only_first_name'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Header';
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