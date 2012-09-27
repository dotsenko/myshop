<?php


class Df_Tweaks_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Tweaks_Helper_Settings_Account
	 */
	public function account () {

		/** @var Df_Tweaks_Helper_Settings_Account $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Account::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Account);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Banners
	 */
	public function banners () {

		/** @var Df_Tweaks_Helper_Settings_Banners $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Banners::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Banners);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Cart
	 */
	public function cart () {

		/** @var Df_Tweaks_Helper_Settings_Cart $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Cart::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Cart);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Catalog
	 */
	public function catalog () {

		/** @var Df_Tweaks_Helper_Settings_Catalog $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Catalog::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Catalog);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Checkout
	 */
	public function checkout () {

		/** @var Df_Tweaks_Helper_Settings_Checkout $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Checkout::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Checkout);

		return $result;

	}




	/**
	 * @return Df_Tweaks_Helper_Settings_Header
	 */
	public function header () {

		/** @var Df_Tweaks_Helper_Settings_Header $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Header::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Header);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Newsletter
	 */
	public function newsletter () {

		/** @var Df_Tweaks_Helper_Settings_Newsletter $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Newsletter::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Newsletter);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_Footer
	 */
	public function footer () {

		/** @var Df_Tweaks_Helper_Settings_Footer $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Footer::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Footer);

		return $result;

	}




	/**
	 * @return Df_Tweaks_Helper_Settings_Labels
	 */
	public function labels () {

		/** @var Df_Tweaks_Helper_Settings_Labels $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Tweaks_Helper_Settings_Labels $result  */
			$result = Mage::helper (Df_Tweaks_Helper_Settings_Labels::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Tweaks_Helper_Settings_Labels);

		}

		return $result;

	}
	
	
	
	
	
	/**
	 * @return Df_Tweaks_Helper_Settings_Jquery
	 */
	public function jquery () {

		/** @var Df_Tweaks_Helper_Settings_Jquery $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Tweaks_Helper_Settings_Jquery $result  */
			$result = Mage::helper (Df_Tweaks_Helper_Settings_Jquery::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Tweaks_Helper_Settings_Jquery);

		}

		return $result;

	}	
	






	/**
	 * @return Df_Tweaks_Helper_Settings_Paypal
	 */
	public function paypal () {

		/** @var Df_Tweaks_Helper_Settings_Paypal $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Paypal::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Paypal);

		return $result;

	}




	/**
	 * @return Df_Tweaks_Helper_Settings_Poll
	 */
	public function poll () {

		/** @var Df_Tweaks_Helper_Settings_Poll $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Poll::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Poll);

		return $result;

	}



	/**
	 * @return Df_Tweaks_Helper_Settings_RecentlyComparedProducts
	 */
	public function recentlyComparedProducts () {

		/** @var Df_Tweaks_Helper_Settings_RecentlyComparedProducts $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_RecentlyComparedProducts::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_RecentlyComparedProducts);

		return $result;

	}





	/**
	 * @return Df_Tweaks_Helper_Settings_RecentlyViewedProducts
	 */
	public function recentlyViewedProducts () {

		/** @var Df_Tweaks_Helper_Settings_RecentlyViewedProducts $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_RecentlyViewedProducts::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_RecentlyViewedProducts);

		return $result;

	}





	/**
	 * @return Df_Tweaks_Helper_Settings_Tags
	 */
	public function tags () {

		/** @var Df_Tweaks_Helper_Settings_Tags $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Tags::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Tags);

		return $result;

	}





 	/**
	 * @return Df_Tweaks_Helper_Settings_Theme
	 */
	public function theme () {

		/** @var Df_Tweaks_Helper_Settings_Theme $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Theme::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Theme);

		return $result;

	}





	/**
	 * @return Df_Tweaks_Helper_Settings_Wishlist
	 */
	public function wishlist () {

		/** @var Df_Tweaks_Helper_Settings_Wishlist $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Settings_Wishlist::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Settings_Wishlist);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings';
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