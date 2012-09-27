<?php


class Df_Seo_Helper_Settings_Catalog_Category extends Df_Core_Helper_Settings {



	/**
	 * Скрывать описание товарного раздела
	 * со всех страниц товарного раздела, кроме первой?
	 *
	 * @return boolean
	 */
	public function needHideDescriptionFromNonFirstPages () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/catalog_category/hide_description_from_non_first_pages'
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
		return 'Df_Seo_Helper_Settings_Catalog_Category';
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
