<?php


class Df_Catalog_Helper_Settings_Navigation extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks/illustrated_catalog_navigation/enabled'
			)
		;

		df_result_boolean ($result);


		return $result;

	}


	/**
	 * @return string
	 */
	public function getNumberOfColumns () {
		return
			Mage::getStoreConfig (
				'df_tweaks/illustrated_catalog_navigation/number_of_columns'
			)
		;
	}



	/**
	 * @return string
	 */
	public function getPosition () {
		return
			Mage::getStoreConfig (
				'df_tweaks/illustrated_catalog_navigation/position'
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Settings_Navigation';
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