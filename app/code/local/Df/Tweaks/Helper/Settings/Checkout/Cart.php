<?php


class Df_Tweaks_Helper_Settings_Checkout_Cart extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getRemoveCrosssellBlock () {
		return
			Mage::getStoreConfig (
				'df_tweaks/checkout_cart/remove_crosssell_block'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveDiscountCodesBlock () {
		return
			Mage::getStoreConfig (
				'df_tweaks/checkout_cart/remove_discount_codes_block'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveShippingAndTaxEstimation () {
		return
			Mage::getStoreConfig (
				'df_tweaks/checkout_cart/remove_shipping_and_tax_estimation'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Checkout_Cart';
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