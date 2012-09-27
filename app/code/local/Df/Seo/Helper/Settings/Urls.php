<?php


class Df_Seo_Helper_Settings_Urls extends Df_Core_Helper_Settings {


	/**
	 * «Create Permanent Redirect for old URLs if Url key changed» for legacy Magento versions
	 *
	 * @return boolean
	 */
	public function getSaveRewritesHistory () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/urls/save_rewrites_history'
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * Whether to preserve cyrillic letters in category & product urls
	 *
	 * @return boolean
	 */
	public function getPreserveCyrillic () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/urls/preserve_cyrillic'
			)
		;

		df_result_boolean ($result);

		return $result;

	}



	/**
	 * @return boolean
	 */
	public function getAddCategoryToProductUrl () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				Mage_Catalog_Helper_Product::XML_PATH_PRODUCT_URL_USE_CATEGORY
			)
		;

		df_result_boolean ($result);

		return $result;

	}



	/**
	 * @return boolean
	 */
	public function getFixAddCategoryToProductUrl () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/urls/fix_add_category_to_product_url'
			)
		;

		df_result_boolean ($result);

		return $result;

	}


	/**
	 * @return boolean
	 */
	public function getRedirectToCanonicalProductUrl () {
		return
			Mage::getStoreConfig (
				'df_seo/urls/redirect_to_canonical_product_url'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Settings_Urls';
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
