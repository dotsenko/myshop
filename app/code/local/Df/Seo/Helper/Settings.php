<?php


class Df_Seo_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Seo_Helper_Settings_Catalog
	 */
	public function catalog () {

		/** @var Df_Seo_Helper_Settings_Catalog $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Seo_Helper_Settings_Catalog $result  */
			$result = Mage::helper (Df_Seo_Helper_Settings_Catalog::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Seo_Helper_Settings_Catalog);

		}

		return $result;

	}






	/**
	 * @return Df_Seo_Helper_Settings_Common
	 */
	public function common () {

		/** @var Df_Seo_Helper_Settings_Common $result  */
		$result = Mage::helper (Df_Seo_Helper_Settings_Common::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Settings_Common);

		return $result;

	}



	/**
	 * @return Df_Seo_Helper_Settings_Images
	 */
	public function images () {

		/** @var Df_Seo_Helper_Settings_Images $result  */
		$result = Mage::helper (Df_Seo_Helper_Settings_Images::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Settings_Images);

		return $result;

	}



	/**
	 * @return Df_Seo_Helper_Settings_Html
	 */
	public function html () {

		/** @var Df_Seo_Helper_Settings_Html $result  */
		$result = Mage::helper (Df_Seo_Helper_Settings_Html::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Settings_Html);

		return $result;

	}


	/**
	 * @return Df_Seo_Helper_Settings_Urls
	 */
	public function urls () {

		/** @var Df_Seo_Helper_Settings_Urls $result  */
		$result = Mage::helper (Df_Seo_Helper_Settings_Urls::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Settings_Urls);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Settings';
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