<?php

class Df_Cms_Model_ContentsMenu_PageType extends Df_Core_Model_Abstract {


	const ACCOUNT = 'account';
	const CATALOG_PRODUCT_LIST = 'catalog_product_list';
	const CATALOG_PRODUCT_VIEW = 'catalog_product_view';
	const CMS_FOREIGN = 'cms_foreign';
	const CMS_OWN = 'cms_own';
	const FRONT = 'front';
	const OTHER = 'other';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_ContentsMenu_PageType';
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
