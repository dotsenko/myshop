<?php


class Df_Seo_Helper_Settings_Html extends Df_Core_Helper_Settings {


	/**
	 * Default pattern for TITLE tag
	 *
	 * @return string
	 */
	public function getDefaultPatternForProductTitleTag () {
		return
			Mage::getStoreConfig (
				'df_seo/html/product_title_tag_default_pattern'
			)
		;
	}


	/**
	 * Whether to append category name to product TITLE tag
	 *
	 * @return boolean
	 */
	public function getAppendCategoryNameToProductTitleTag () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/html/append_category_name_to_product_title_tag'
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
		return 'Df_Seo_Helper_Settings_Html';
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
