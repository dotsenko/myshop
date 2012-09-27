<?php

class Df_Cms_Helper_Settings_Hierarchy extends Df_Core_Helper_Settings {
	
	

	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_cms/hierarchy/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needAddToCatalogMenu () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_cms/hierarchy/add_to_catalog_menu'
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
		return 'Df_Cms_Helper_Settings_Hierarchy';
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