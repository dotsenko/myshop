<?php

class Df_Promotion_Helper_Settings extends Df_Core_Helper_Settings {




	/**
	 * @return Df_Banner_Helper_Settings
	 */
	public function banners () {

		/** @var Df_Banner_Helper_Settings $result  */
		$result = Mage::helper (Df_Banner_Helper_Settings::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Banner_Helper_Settings);

		return $result;
	}





	/**
	 * @return Df_PromoGift_Helper_Settings
	 */
	public function gifts () {

		/** @var Df_PromoGift_Helper_Settings $result  */
		$result = Mage::helper (Df_PromoGift_Helper_Settings::getNameInMagentoFormat());

		df_assert ($result instanceof Df_PromoGift_Helper_Settings);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Promotion_Helper_Settings';
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