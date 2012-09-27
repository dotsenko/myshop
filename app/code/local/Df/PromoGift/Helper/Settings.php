<?php

class Df_PromoGift_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function enableAddToCartButton () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/enable_add_to_cart_button'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needShowChooserOnProductListPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/chooser__show_on_product_list'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needShowChooserOnProductViewPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/chooser__show_on_product_view'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needShowChooserOnCartPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/chooser__show_on_cart_page'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needShowChooserOnFrontPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/chooser__show_on_front_page'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function needShowChooserOnCmsPage () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/chooser__show_on_cms_pages'
			)
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @return boolean
	 */
	public function getAutoAddToCart () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_promotion/gifts/auto_add_to_cart'
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Helper_Settings';
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